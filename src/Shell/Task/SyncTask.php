<?php
namespace App\Shell\Task;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class SyncTask extends Shell {
	// var $uses = array('Users'); // same as controller var $uses
	private $redirect_uri = 'https://mightycoco.pointcode.de/dpnet/subsystem/authenticate';
	
	function execute($id = null) {
		if(empty($id)) {
			$id = $this->getNextDatasourceId();
			$this->setNextDatasourceId($id);
		}
		$datasource = TableRegistry::get('Datasource')->findBySource($id)->first();//;->find('all')->where(['source'=>$id])->first();
		
		$access_token = $this->getAccessToken();

		$fb = $this->getFacebook();
		
		$response = $fb->get("/$id/events", $access_token);
		$eventsEdge = $response->getGraphEdge()->asArray();

		$cmpDate = function($a, $b)
		{
			if ($a['start_time']->getTimestamp() == $b['start_time']->getTimestamp()) {
				return 0;
			}
			return ($a['start_time']->getTimestamp() < $b['start_time']->getTimestamp()) ? -1 : 1;
		};
		usort($eventsEdge, $cmpDate);
		foreach($eventsEdge as &$event) {
			$event['datasource'] = $datasource;
		}
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
	
	public function setNextDatasourceId($id) {
		$settings = TableRegistry::get('Settings');
		$setting = $settings->find('all')->where(['skey'=>'lastSyncedDSId'])->first();
		if(empty($setting->value)) {
			$setting = $settings->newEntity();
			$setting->skey = "lastSyncedDSId";
		}
		$setting->value = $id;
		$settings->save($setting);
	}
	
	public function getNextDatasourceId() {
		// get the last synched id from settings or fetch the first entity from datasources
		$last = TableRegistry::get('Settings')->find('all')->where(['skey'=>'lastSyncedDSId'])->first();
		if(empty($last)) $last = TableRegistry::get('Datasource')->find('all')->first();
		if(empty($last)) throw new Exception("No datasoutrces available");
		
		if(!empty($last['source'])) {
			$id = $last['source'];
		} else {
			$id = $last['value'];
		}
		
		$datasources = TableRegistry::get('Datasource')->find('all');
		$found = false;
		
		foreach($datasources as $ds) {
			if($found) {
				return $ds['source'];
			}
			if($ds['source'] == $id) {
				$found = true;
			}
		}

		// last item - start over from first
		return TableRegistry::get('Datasource')->find('all')->first()['source'];
	}
}
?>