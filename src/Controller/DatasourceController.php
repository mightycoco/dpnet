<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Shell\Task\SyncTask;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use \DateTime;
use Cake\Event\Event;
use App\Shell\ConsoleShell;

/**
 * Datasource Controller
 *
 * @property \App\Model\Table\DatasourceTable $Datasource
 */
class DatasourceController extends AppController
{

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->Auth->allow( 'sync' );
	}

	public function sync() {
		$task = new ConsoleShell();
		$task->Sync = new SyncTask();
		$e = $task->sync(null);
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
        $datasource = $this->paginate($this->Datasource);

        $this->set(compact('datasource'));
        $this->set('_serialize', ['datasource']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $datasource = $this->Datasource->newEntity();
        if ($this->request->is('post')) {
            $datasource = $this->Datasource->patchEntity($datasource, $this->request->data);
            $datasource->created = new DateTime('now');
            $datasource->modified = new DateTime('now');
            if ($this->Datasource->save($datasource)) {
                $this->Flash->success(__('The datasource has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The datasource could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('datasource'));
        $this->set('_serialize', ['datasource']);
    }
    
	public function addFromUri() {
		//$uri = (object)array('uri' => 'http://test.com');
		$uri = $this->request->query('uri');
		
		if ($this->request->is('post') || !empty($this->request->query('uri'))) {
        	$uri = !empty($this->request->query('uri')) ? $this->request->query('uri') : $this->request->data['uri'];
			$uri = preg_replace('/\/events[\/]?/', '', $uri);
			
			if(stristr($uri, "/pg/")) {
				$tag = preg_replace('/^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$/', "$6", $uri);
			} else {
				$tag = preg_replace('/^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$/', "$6", $uri);
			}
			$fbc = (new SyncTask);
			
			$access_token = $fbc->getAccessToken();
			$fb = $fbc->getFacebook();
			$response = $fb->get("/$tag", $access_token);

			$type = "page";
			$source = $response->getDecodedBody()['id'];
			$description = $response->getDecodedBody()['name'];
			
			$datasource = $this->Datasource->newEntity();
			$datasource = $this->Datasource->patchEntity($datasource, array(
				'type' => $type,
				'source' => $source,
				'description' => $description,
				'trusted' => false
			));
			
			if($this->Datasource->find()->where(['source'=>$source])->first() != null) {
				$this->Flash->error(__("'$description' already exists with the id '$source'."));
			} else {
				$datasource->modified = new DateTime('now');
				$datasource->created = new DateTime('now');
				if ($this->Datasource->save($datasource)) {
					$this->Flash->success(__('The datasource has been saved.'));
					$newDs = $this->Datasource->find()->where(['source'=>$source])->first();
					return $this->redirect(['action' => 'edit', $newDs->uuid]);
				} else {
					$this->Flash->error(__('The datasource could not be saved. Please, try again.'));
				}
   			}
		}
		$this->set(compact('uri'));
		$this->set('_serialize', ['uri']);
	}

    /**
     * Edit method
     *
     * @param string|null $id Datasource id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $datasource = $this->Datasource->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $datasource = $this->Datasource->patchEntity($datasource, $this->request->data);
            $datasource->modified = new DateTime('now');
            if ($this->Datasource->save($datasource)) {
                $this->Flash->success(__('The datasource has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The datasource could not be saved. Please, try again.'));
            }
        }
        
        $named = Router::parseNamedParams($this->request);
		$events = [];
        if(end($named->params['pass']) == 'fetch') {
			$eventsTable = TableRegistry::get('Events');
    
        	$fbc = (new SyncTask);
        	$raw_events = $fbc->execute($datasource->source);
        	foreach($raw_events as $event) {
        		$entity = $eventsTable->newEntity();
        		$entity->fromRaw($event);
        		if((new Datetime($entity->event_start))->getTimestamp() < time()) {
        			$entity->event_name = "[PAST] " . $entity->event_name;
        			$entity->past = true;
        		}
        		array_push($events, $entity);	
        	}
        }
        
		usort($events, function($a, $b)
		{
		    return $a->event_start < $b->event_start;
		});

        
        $this->set(compact('datasource'));
        $this->set(compact('events'));

		$this->set('_serialize', ['datasource','events']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Datasource id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $datasource = $this->Datasource->get($id);
        if ($this->Datasource->delete($datasource)) {
            $this->Flash->success(__('The datasource has been deleted.'));
        } else {
            $this->Flash->error(__('The datasource could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
