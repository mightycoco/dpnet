<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use App\Shell\Task\SyncTask;
use Cake\Core\Configure;


/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 */
class DashboardController extends AppController
{
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
	}
	
	public function index() {
		$events = TableRegistry::get('Events');
		$datasources = TableRegistry::get('Datasource')->find('all');
		$this->set(compact('datasources'));
        $this->set(compact('events'));

		$this->set('_serialize', ['datasources','events']);
		
		try {
			$task = (new SyncTask());
			
			$raw = $task
				->getFacebook()
				->get("/me", $task->getAccessToken())
				->getDecodedBody();
		} catch (\Exception $ex) {
			$ref = Configure::read('App.fullBaseUrl');
			$msg = "Facebook: ($ref) ".$ex->getMessage();
			$this->Flash->error(__($msg));
		}
	}
}