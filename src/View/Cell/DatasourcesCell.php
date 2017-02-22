<?php
namespace App\View\Cell;

use Cake\View\Cell;
use App\Shell\Task\SyncTask;
use Cake\View\Helper\FlashHelper;
use Cake\Datasource\ConnectionManager;

class DatasourcesCell extends Cell
{

    public function display()
    {
        $this->loadModel('Datasource');
        $this->loadModel('Events');
        $sum = $this->Datasource->find('all');
        $this->set('sum', $sum->count());
        $this->set('testResult', array('name'=>'non'));

		$connection = ConnectionManager::get('default');
		
        $all_per_ds = $connection->execute(
		    'SELECT d.uuid AS id, d.source AS `source`, d.description AS `description`, COUNT(e.id) AS `count` FROM datasource d JOIN events e ON d.source=e.datasource_id GROUP BY d.description;'
		)->fetchAll('assoc');
        $approved_per_ds = $connection->execute(
		    'SELECT d.uuid AS id, d.source AS `source`, d.description AS `description`, COUNT(e.id) AS `count` FROM datasource d JOIN events e ON d.source=e.datasource_id WHERE e.event_approval=\'approved\' GROUP BY d.description;'
		)->fetchAll('assoc');
        $rejected_per_ds = $connection->execute(
		    'SELECT d.uuid AS id, d.source AS `source`, d.description AS `description`, COUNT(e.id) AS `count` FROM datasource d JOIN events e ON d.source=e.datasource_id WHERE e.event_approval=\'rejected\' GROUP BY d.description;'
		)->fetchAll('assoc');
        $pending_per_ds = $connection->execute(
		    'SELECT d.uuid AS id, d.source AS `source`, d.description AS `description`, COUNT(e.id) AS `count` FROM datasource d JOIN events e ON d.source=e.datasource_id WHERE e.event_approval=\'pending\' GROUP BY d.description;'
		)->fetchAll('assoc');
		
		$ds_usage = [];
		
		foreach($all_per_ds as $usg) {
			$approved = array_filter($approved_per_ds, function ($e) use ($usg) { return $e['id'] == $usg['id']; });
			$rejected = array_filter($rejected_per_ds, function ($e) use ($usg) { return $e['id'] == $usg['id']; });
			$pending = array_filter($pending_per_ds, function ($e) use ($usg) { return $e['id'] == $usg['id']; });
			$approved = reset($approved);
			$rejected = reset($rejected);
			$pending = reset($pending);
			$approved = $approved['source'] ? $approved['count'] : "";
			$rejected = $rejected['source'] ? $rejected['count'] : "";
			$pending = $pending['source'] ? $pending['count'] : "";
			$ds_usage[$usg['source']] = [
				'id' => $usg['id'],
				'source' => $usg['source'],
				'description' => $usg['description'],
				'approved' => $approved,
				'rejected' => $rejected,
				'pending' => $pending,
				'sum' => $usg['count']
			];
		}
		
		$this->set('ds_usage', $ds_usage);
        
        $testResult = array();
    	if ($this->request->is('post')) {
    		
    		$uri = $this->request->data['test_uri'];
    		$tag = trim(preg_replace('/^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$/', "$6", $uri), " \t\n\r\0\x0B/");
    		
    		if($tag) {
	    		$fbc = (new SyncTask);
				$access_token = $fbc->getAccessToken();
	
	    		$fb = $fbc->getFacebook();
	    		
				$response = $fb->get("/$tag", $access_token);
				
				$testResult['source'] = $response->getDecodedBody()['id'];
				$testResult['name'] = $response->getDecodedBody()['name'];
				$testResult['events'] = array();
				
				$raw_events = $fbc->execute($testResult['source']);
				foreach($raw_events as $event) {
	        		$entity = $this->Events->newEntity();
	        		$entity->fromRaw($event);
	        		array_push($testResult['events'], $entity);	
				}
				
				if($this->Datasource->find()->where(['source'=>$testResult['source']])->first() != null) {
					$testResult['error'] = __("'".$testResult['name']."' already exists with the id '".$testResult['source']."'.");
				}
    		}
			
    	}
		$this->set(compact('testResult'));
		return $this;
    }
    
    public function uriForm() {
    }
    
    public function uriTestForm() {
    	
    }

}