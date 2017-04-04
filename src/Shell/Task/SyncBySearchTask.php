<?php
namespace App\Shell\Task;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class SyncBySearchTask extends Shell {
	// var $uses = array('Users'); // same as controller var $uses
	private $redirect_uri = 'https://mightycoco.pointcode.de/dpnet/subsystem/authenticate';
	
	function execute() {
		$publicEvents = $this->eventsQuery();
		$placesEvents = $this->placesQuery();
		
		return array_merge($publicEvents, $placesEvents);
	}
	
	function placesQuery() {
		// search?q=ebm | industrial | wave | industrial | underground | alternative | synth&type=page&limit=100&fields=name,id,location,events
		$access_token = $this->getAccessToken();
		$eventsTable = TableRegistry::get('Events');
		$events = [];
		$after = "";

		$fb = $this->getFacebook();
		
		$paging = 0;
		
		while($after != "..." && $paging < 5) {
			$response = $fb->get("search?q=ebm | industrial | wave | industrial | underground | alternative | synth&type=page&limit=100&fields=name,id,location,events&after=$after&since=".time(), $access_token);
			$placesEdge = $response->getGraphEdge()->asArray();
			$after = $response->getGraphEdge()->getNextCursor();
			if(!$after) $after = "...";
	
			foreach($placesEdge as $place) {
				$dss = TableRegistry::get('Datasource')->find()->where(['source' => $place['id']])->first();
				if(!$dss) {
					if(isset($place['events'])) {
						// echo $place['name']." ".$place['id']."\n";
						foreach($place['events'] as $event) {
							if(isset($place['location']) && !isset($event['place'])) {
								$event['palce'] = [];
								$event['palce']['name'] = $place['name'];
								$event['palce']['id'] = $place['id'];
								$event['palce']['location'] = $place['location'];
							}
							$entity = $eventsTable->newEntity();
				    		$event['datasource'] = 0;
							$entity->fromRaw($event);
							if($entity->is_location_ok()) {
								//echo "  - {$event['name']}\n";
								$events[] = $event;
							}
						}
					}
				}
			}
			$paging++;
		}
		
		$cmpDate = function($a, $b)
		{
			if ($a['start_time']->getTimestamp() == $b['start_time']->getTimestamp()) {
				return 0;
			}
			return ($a['start_time']->getTimestamp() < $b['start_time']->getTimestamp()) ? -1 : 1;
		};
		usort($events, $cmpDate);
		/*foreach($eventsEdge as &$event) {
			$event['datasource'] = $datasource;
		}*/
		return $events;
	}
	
	function eventsQuery() {
		$access_token = $this->getAccessToken();

		$fb = $this->getFacebook();
		
		$response = $fb->get("search?q=ebm | industrial | wave | industrial | underground | alternative | synth&type=event&limit=100&fields=end_time,name,place,start_time,id,owner&since=".time(), $access_token);
		$eventsEdge = $response->getGraphEdge()->asArray();

		$cmpDate = function($a, $b)
		{
			if ($a['start_time']->getTimestamp() == $b['start_time']->getTimestamp()) {
				return 0;
			}
			return ($a['start_time']->getTimestamp() < $b['start_time']->getTimestamp()) ? -1 : 1;
		};
		usort($eventsEdge, $cmpDate);
		/*foreach($eventsEdge as &$event) {
			$event['datasource'] = $datasource;
		}*/
		return $eventsEdge;
	}
	
	public function getAccessToken() {
		$table = TableRegistry::get('Settings');
		$orm = $table->find()->where(['skey' => 'access_token'])->first();
		return $orm->get('value');
	}
	
	public function getFacebook() {
		$app_id = Configure::read('Facebook.app_id');
		$secret = Configure::read('Facebook.secret');
		return new \Facebook\Facebook([
			'app_id' => $app_id,
			'app_secret' => $secret,
			'default_graph_version' => 'v2.8'
		]);
	}
}
?>