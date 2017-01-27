<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Datasource\ConnectionManager;

class EventsCell extends Cell
{

    public function display()
    {
    	//  select count(created) as sum, DATE(created) date from events GROUP BY date;
    	/*
    	$db = $this->Order->getDataSource();
		$shownames = $db->fetchAll(
		    'SELECT CONCAT(sm_schools.title, " - ", sm_shows.title)
		    FROM sm_shows
		    LEFT JOIN sm_schools
		    ON sm_shows.school_id = sm_schools.id
		    WHERE CONCAT(sm_schools.title, " - ", sm_shows.title) LIKE ?',
		    array($term)
		);
		*/
        $this->loadModel('Events');
        
        $connection = ConnectionManager::get('default');
        
        $usage_created = $connection->execute(
		    'SELECT count(created) as sum, DATE(created) as date FROM events GROUP BY date'
		)->fetchAll('assoc');

        $approved = $this->Events->find('all')->where(['event_approval'=>'approved']);
        $rejected = $this->Events->find('all')->where(['event_approval'=>'rejected']);
        $pending = $this->Events->find('all')->where(['event_approval'=>'pending']);
        $sum = $this->Events->find('all');
        $this->set('approved', $approved->count());
        $this->set('rejected', $rejected->count());
        $this->set('pending', $pending->count());
        $this->set('sum', $sum->count());
        
        $this->set('usage_created', $usage_created);
    }
    
    public function uriForm() {
    }

}