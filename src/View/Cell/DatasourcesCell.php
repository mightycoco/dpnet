<?php
namespace App\View\Cell;

use Cake\View\Cell;

class DatasourcesCell extends Cell
{

    public function display()
    {
        $this->loadModel('Datasource');
        $sum = $this->Datasource->find('all');
        $this->set('sum', $sum->count());
    }
    
    public function uriForm() {
    }

}