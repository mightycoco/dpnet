<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Shell\Task\SyncTask;
use App\Model\Table\DatasourceTable;
use Cake\Core\Configure;

/**
 * Subsystem Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 */
class SubsystemController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
    	$this->set('access_token', $this->getAccessToken());
    }
    
    private function getAccessToken() {
    	$table = TableRegistry::get('Settings');
    	$orm = $table->find()->where(['skey' => 'access_token'])->first();
    	if($orm) return $orm->get('value');
    	return "";
    }

    private function setAccessToken($token) {
    	$table = TableRegistry::get('Settings');
    	$orm = $table->find()->where(['skey' => 'access_token'])->first();
    	
    	if(!$orm) {
    		$orm = $table->newEntity();
    	}
		$orm->set(['skey'=>'access_token','value'=>$token]);
		$table->save($orm);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
    }
    
    public function authenticate() {
    	$access_token = null;
    	
    	$redirect_url = $secret = Configure::read('Facebook.redirect_url');
    	$app_id = $secret = Configure::read('Facebook.app_id');
    	$secret = $secret = Configure::read('Facebook.secret');

    	$fb = (new SyncTask())->getFacebook();
    	
		$code = @$_REQUEST["code"];

		if(empty($code)) {
			$_SESSION['state'] = md5(uniqid(rand(), TRUE));
			
			$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" . $app_id 
						. "&redirect_uri=" . urlencode($redirect_url) 
						. "&state=" . $_SESSION['state'] 
						. "&scope=email,user_events";
			echo("<script> top.location.href='" . $dialog_url . "'</script>");
			exit();
		}
		
		if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state']) || $code) {
			$token_url = "https://graph.facebook.com/oauth/access_token?"
				. "client_id=" . $app_id 
				. "&redirect_uri=" . urlencode($redirect_url)
				. "&client_secret=" . $secret 
				. "&code=" . $code;
			
			$response = file_get_contents($token_url);
			$params = null;
			parse_str($response, $params);
			$access_token = $params['access_token'];
		}
		
		$response = null;
		
		try {
			// Get the \Facebook\GraphNodes\GraphUser object for the current user.
			// If you provided a 'default_access_token', the '{access-token}' is optional.
			$response = $fb->get('/me', $access_token);
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
		}
		
		if($access_token) {
			$this->setAccessToken($access_token);
		}
		
		if($response && $access_token) {
			$me = $response->getGraphUser();
			$this->Flash->success(__('Received valid token for "'.$me->getName().'"'));
		} else {
			$this->Flash->error(__('Couldn\'t request a token'));
		}

    	return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
    }
      
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }
    
}
