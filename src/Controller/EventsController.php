<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Shell\Task\SyncBySearchTask;
use \DateTime;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use App\Controller\Helper\SlackPushConnector;
use App\Shell\ConsoleShell;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 */
class EventsController extends AppController
{
	
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->Auth->allow([ 'addFromUri','exportIcs','sync' ]);
	}
	
	public function sync() {
		$task = new ConsoleShell();
		$task->SyncBySearch = new SyncBySearchTask();
		$e = $task->querySync(null);
		echo "OK";
		exit(1);
	}

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
    	$where = [];
    	if(isset($this->request->query['event_start'])) {
    		if($this->request->query['event_start'] == "all") {
    		} else if($this->request->query['event_start'] == "upcoming") {
    			$where['event_start >='] = new DateTime('now');
    		} else if($this->request->query['event_start'] == "past") {
    			$where['event_start <'] = new DateTime('now');
    		}
    	}
    	if(isset($this->request->query['event_approval']) && $this->request->query['event_approval'] != 'all') {
    		$where['event_approval'] = $this->request->query['event_approval'];
    	}
        $events = $this->paginate($this->Events->find('all')->where($where));

        $this->set(compact('events'));
        $this->set('_serialize', ['events']);
    }
    
    public function filter($filter)
    {
    	$where = ['event_approval'=>$filter];
    	if(isset($this->request->query['filter'])) {
    		$filter = $this->request->query['filter'];
    		if($filter == "upcoming") {
    			$where['event_start >='] = new DateTime('now');
    		}
    	}
        $events = $this->paginate($this->Events->find('all')->where($where));

        $this->set(compact('events'));
        $this->set('_serialize', ['events']);
        $this->render('index');
    }
    
    /**
     * View method
     *
     * @param string|null $id Event id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function approve($id = null)
    {
        $event = $this->Events->get($id);
        $event->event_approval = 'approved';
        $event->modified = new DateTime('now');
        $this->Events->save($event);
		$this->Flash->success(__('Approved "'.$event->event_name.'"'));
		$this->redirect($this->referer());
    }
    
    /**
     * View method
     *
     * @param string|null $id Event id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function reject($id = null)
    {
        $event = $this->Events->get($id);
        $event->event_approval = 'rejected';
        $event->modified = new DateTime('now');
        $this->Events->save($event);
		$this->Flash->error(__('Rejected "'.$event->event_name.'"'));
		$this->redirect($this->referer());
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $event = $this->Events->newEntity();
        if ($this->request->is('post')) {
            $event = $this->Events->patchEntity($event, $this->request->data);
            $event->modified = new DateTime('now');
            $event->created = new DateTime('now');
            if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The event could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('event'));
        $this->set('_serialize', ['event']);
    }
    
    public function exportIcs($id) {
  		$event = $this->Events->get($id);
		$start = $event->event_start;
		$end = $event->event_end ? $event->event_end : "";
		$location = "$event->loc_street, $event->loc_city / $event->loc_country";
		$subject = $event->event_name;
	 	$url = " http://facebook.com/" . $event->id;
	 	$description = "$event->place_name $url";
	 	if($start) $start = date("Ymd\THis\Z",strtotime($start));
	 	if($end) $end = date("Ymd\THis\Z",strtotime($end));
	 	$dstamp = date("Ymd\THis\Z");

	 	header("Content-Type: text/calendar");
		echo <<<END
BEGIN:VCALENDAR
VERSION:2.0
PRODID:https://dark-party.net//NONSGML v1.0//EN
DTSTAMP: $dstamp
ORGANIZER: ""
BEGIN:VEVENT
UID: $event->id
DTSTART: $start
DTEND: $end
LOCATION: $location
URL: $url
SUMMARY: $subject
DESCRIPTION: $description
END:VEVENT
END:VCALENDAR
END;
		exit(0);
    }
    
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function addFromUri()
    {
    	// https://www.facebook.com/events/1876698622558633/
    	$uri = (object)array('uri' => 'http://test.com');

        if ($this->request->is('post') || !empty($this->request->query('uri'))) {
        	$uri = !empty($this->request->query('uri')) ? $this->request->query('uri') : $this->request->data['uri'];

        	preg_match('/\d+/', $uri, $id);
        	$id = $id[0];

        	try {
        		// do we know about this event?
	        	if($this->Events->findById($id)->first()) {
        			$stored = $this->Events->get($id);
					$this->Flash->success(__('Thank you. This event "'.$stored->event_name.'" already exists and is in state "'.$stored->event_approval.'".'));
				} else {
		        	$task = (new SyncTask());

		    		// try to look up the parent page
					$raw = $task
						->getFacebook()
						->get("/".$id."?fields=parent_group", $task->getAccessToken())
						->getDecodedBody();

					$process = true;
					$group_id = null;

					// does the event has a group/page as it's parent?
					if(isset($raw['parent_group'])) {
						$group_id = $raw['parent_group']['id'];

						// do we already synchronize this group/page?
						$datasource = TableRegistry::get('Datasource')->findBySource($group_id)->first();
						if($datasource) {
							// TODO: if the Datsource doesn't exist, we could add this as a new UNAPPROVED datasource

							// look if we would find this requested event in the next sync run, as well
							$raw = $task
								->getFacebook()
								->get("/".$group_id."/events?fields=id", $task->getAccessToken())
								->getDecodedBody();
							foreach($raw['data'] as $event_id) {
								if($event_id['id'] == $id) {
									$process = false;
									$this->Flash->success(__('Thank you. This event from "'.$datasource->description.'" will be processed the next time we look up that page/group. Just be a little bit patient.'));
								}
							}
						}
					}
		
					if($process) {
			        	$raw = $task
								->getFacebook()
								->get("/".$id, $task->getAccessToken())
								->getGraphNode();
								
						$raw['datasource'] = 0;
						$event = $this->Events->newEntity();
						$event->fromRaw($raw);
						$event->modified = new DateTime('now');
			            $event->created = new DateTime('now');
			            $event->event_approval = 'pending';
			            if(!empty($group_id) && empty($event->datasource_id)) $event->datasource_id = $group_id;
			
			            $cover = $task
									->getFacebook()
									->get("/".$id."/?fields=cover", $task->getAccessToken())
									->getDecodedBody();
						$event->cover = @$cover['cover']['source'];
			
			            $this->Events->save($event);
	
						$this->Flash->success(__('Thank you. The event "'.$event->event_name.'" is going to be processed by our team and is currently "'.$event->event_approval.'".'));
						
						$slack = new SlackPushConnector();
    					$slack->push("New pending event '.$event->event_name.'", "date", "dpnet-bot", "notifications");
					}
		            
		            $event_location = Router::fullbaseUrl().Router::url(['action' => 'edit', $id]);
		            $pending_location = Router::fullbaseUrl().Router::url(['action' => 'index', 'event_approval' => 'pending']);
				}
        	} catch(\Exception $e) {
				$this->Flash->success(__('Error parsing the event information. ' . $e->getMessage()));
        	}

			if(preg_match('/^' . preg_quote("http", '/') . '/', $uri)) {
				$this->set('forward', $uri);
			}
			if ($this->Auth->user()) {
				$this->set('back', Router::fullbaseUrl().Router::url(['action' => 'edit', $id]));
			}

			return $this->render('redirectAfterAdd');
        }

        $this->set(compact('uri'));
		$this->set('_serialize', ['uri']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Event id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
    	$event = null;
    	try {
	        $event = $this->Events->get($id, [
	            'contain' => []
	        ]);
    	} catch(\Exception $e) {
			$this->Flash->error(__($e->getMessage()));
    		return $this->redirect(['action' => 'index']);
    	}
        if ($this->request->is(['patch', 'post', 'put'])) {
            $event = $this->Events->patchEntity($event, $this->request->data);
            $event->modified = new DateTime('now');
            if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The event could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('event'));
        $this->set('_serialize', ['event']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Event id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $event = $this->Events->get($id);
        if ($this->Events->delete($event)) {
            $this->Flash->success(__('The event has been deleted.'));
        } else {
            $this->Flash->error(__('The event could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }
}
