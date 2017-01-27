<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

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
	}
}