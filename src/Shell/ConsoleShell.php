<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Log\Log;
use Psy\Shell as PsyShell;
use Cake\ORM\TableRegistry;
use App\Shell\Task\SyncTask;
use \DateTime;

/**
 * Simple console wrapper around Psy\Shell.
 */
class ConsoleShell extends Shell
{
	var $tasks = array('Sync');
    /**
     * Start the shell and interactive console.
     *
     * @return int|null
     */
    public function main()
    {
        if (!class_exists('Psy\Shell')) {
            $this->err('<error>Unable to load Psy\Shell.</error>');
            $this->err('');
            $this->err('Make sure you have installed psysh as a dependency,');
            $this->err('and that Psy\Shell is registered in your autoloader.');
            $this->err('');
            $this->err('If you are using composer run');
            $this->err('');
            $this->err('<info>$ php composer.phar require --dev psy/psysh</info>');
            $this->err('');

            return self::CODE_ERROR;
        }

        $this->out("You can exit with <info>`CTRL-C`</info> or <info>`exit`</info>");
        $this->out('');

        Log::drop('debug');
        Log::drop('error');
        $this->_io->setLoggers(false);
        restore_error_handler();
        restore_exception_handler();

        $psy = new PsyShell();
        $psy->run();
    }

    /**
     * Display help for this console.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = new ConsoleOptionParser('console');
        $parser->description(
            'This shell provides a REPL that you can use to interact ' .
            'with your application in an interactive fashion. You can use ' .
            'it to run adhoc queries with your models, or experiment ' .
            'and explore the features of CakePHP and your application.' .
            "\n\n" .
            'You will need to have psysh installed for this Shell to work.'
        );

        return $parser;
    }
    
    public function datasources() {
    	$datasources = TableRegistry::get('Datasource')->find("all");
    	
    	foreach($datasources as $ds) {
			$this->out($ds['uuid']." ".$ds['source']." ".$ds['description']);
		}
    }
    
    public function event($id) {
    	$event = TableRegistry::get('Events')->find('all')->where(['id' => $id]);
    	print_r($event->first());
    }
    
    public function syncAll() {
		$datasources = TableRegistry::get('Datasource')->find("all");
    	
    	foreach($datasources as $ds) {
			$this->sync($ds['source']);
		}
    }
    
    public function testTag() {
		$eventsTable = TableRegistry::get('Events');
		$events = $eventsTable->find('all')->where(['event_approval !=' => 'pending']);
		
		foreach($events as $event) {
			$weight = $event->getWeight();

			if(($weight > 0 && $event->event_approval == 'approved')
			|| ($weight <= 0 && $event->event_approval == 'rejected')
			|| ($weight == 0 && $event->event_approval == 'pending')) {
				//$this->out("match on '".$event->event_name."': $weight ($tags)");
			} else {
				$this->out("NO match on '".$event->event_name."': $weight $event->event_approval");
			}
		}
    }
    
    public function upgradeEventModel() {
		$eventsTable = TableRegistry::get('Events');
		$events = $eventsTable->find('all')->where(['event_approval !=' => 'rejected', 'cover' => '']);
		
		$ln = $events->count();
		$i = 0;
		
		foreach($events as $event) {
			if(empty($event->cover)) {
				echo "$i / $ln - $event->event_name\n";
				try {
					$task = (new SyncTask());
					// Update the cover image
					$cover = $task
								->getFacebook()
								->get("/".$event->id."/?fields=cover", $task->getAccessToken())
								->getDecodedBody();
					$event->cover = @$cover['cover']['source'];
					$eventsTable->save($event);
				} catch(\Exception $e) {
					echo $e->getMessage();
				}
				sleep(1);
				$i++;
			}
		}
    }
    
    public function sync($id = null) {
		$events = $this->Sync->execute($id);
		$eventsTable = TableRegistry::get('Events');
		
		if(!empty($events[0])) {
			$ds = $events[0]['datasource'];
			$datasource = TableRegistry::get('datasource')->get($ds['uuid']);
			$datasource->accessed = new DateTime('now');
			TableRegistry::get('datasource')->save($datasource);
			// $datasource->created = new DateTime('now');
			$this->out($ds['uuid']." ".$ds['source']." ".$ds['description']);
		}
		
		$pendingAdded = 0;
		$approvedAdded = 0;
		$rejectedAdded = 0;
		
		echo $datasource->reject_zero_weight;
    	
		foreach($events as $event) {
			if($event['start_time']->getTimestamp() > time()
			&& empty($eventsTable->find('all')->where(['id'=>$event['id']])->first())) {
				$this->out("    ".$event['id']." | ".$event['start_time']->format("Y-m-d")." | ".$event['name']);

				$ds = $event['datasource'];
				$approval = 'pending';

				$entity = $eventsTable->newEntity();
				$entity->fromRaw($event);
				$entity->modified = new DateTime('now');
	            $entity->created = new DateTime('now');
				
				$weight = $entity->getWeight();
				
				// get the event cover
				$task = (new SyncTask());
				$cover = $task
							->getFacebook()
							->get("/".$entity->id."/?fields=cover", $task->getAccessToken())
							->getDecodedBody();
				$entity->cover = @$cover['cover']['source'];

				if($ds->trusted) {
					$approval = 'approved';
					$approvedAdded++;
				} else {
					if($weight > 0) {
						$approval = 'approved';
						$approvedAdded++;
					}
					if($weight < 0) {
						$approval = 'rejected';
						$rejectedAdded++;
					}
				}
				
				if($datasource->reject_zero_weight == true && $weight <= 0) {
					$approval = 'rejected';
					$rejectedAdded++;
				}
				
				$entity->event_approval = $approval;

				if ($eventsTable->save($entity)) {
					if($approval == 'pending') {
						$pendingAdded++;
					}
					$this->out("stored $entity->id to database as $approval");
				} else {
					print_r($event->Errors());
				}
			}
		}
		
		$this->out('-> '.$pendingAdded.' pending, '.$approvedAdded.' approved, '.$rejectedAdded.' rejected');

		//if($pendingAdded + $approvedAdded + $rejectedAdded > 0) {
		if($pendingAdded > 0) {
			// TODO: must make a task class with settings in config/app.php
			exec('push -b "New events in '.$ds['description'].' ('.$pendingAdded.' pending, '.$approvedAdded.' approved, '.$rejectedAdded.' rejected)" -m date -e dpnet-bot');
		}
    }
    
    public function getNextDatasource($id) {
    	$this->out($this->Sync->getNextDatasourceId());
    }
}
