<?php
namespace App\Shell\Task;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class SyncBySearchTask extends Shell {
	// var $uses = array('Users'); // same as controller var $uses
	private $redirect_uri = 'https://mightycoco.pointcode.de/dpnet/subsystem/authenticate';
	
	function execute() {
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