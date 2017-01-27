<?php
namespace App\View\Cell;

use Cake\View\Cell;

class EventsCell extends Cell
{

    public function display()
    {
        $this->loadModel('Events');
        $approved = $this->Events->find('all')->where(['event_approval'=>'approved']);
        $rejected = $this->Events->find('all')->where(['event_approval'=>'rejected']);
        $pending = $this->Events->find('all')->where(['event_approval'=>'pending']);
        $sum = $this->Events->find('all');
        $this->set('approved', $approved->count());
        $this->set('rejected', $rejected->count());
        $this->set('pending', $pending->count());
        $this->set('sum', $sum->count());
    }
    
    public function uriForm() {
    }

}