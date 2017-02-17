<?php
namespace App\View\Cell;

use Cake\View\Cell;

class GitCell extends Cell
{
    public function display()
    {
    	if(isset($this->request->query['git'])) {
    		if($this->request->query['git'] == 'pull') {
    			return $this->pull();
    		}
    	}

        $git_status = shell_exec('git git fetch origin ; git log origin/master ^master');
		$this->set(compact('git_status'));
		return $this;
    }
    
    public function pull() {
    	$git_status = shell_exec('git pull');
    	$this->set(compact('git_status'));
    	return $this;
    }

}