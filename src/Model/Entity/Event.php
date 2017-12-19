<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Shell\Task\SyncTask;
use Cake\ORM\TableRegistry;

/**
 * Event Entity
 *
 * @property string $id
 * @property string $datasource
 * @property string $event_description
 * @property string $event_name
 * @property string $event_approval
 * @property \Cake\I18n\Time $event_start
 * @property \Cake\I18n\Time $event_end
 * @property int $place_id
 * @property string $place_name
 * @property string $loc_city
 * @property string $loc_country
 * @property string $loc_street
 * @property string $loc_zip
 * @property float $loc_latitude
 * @property float $loc_longitude
 * @property string $owner_id
 * @property string $owner_name
 */
class Event extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    public function approval_icon() {
    	if($this->event_approval == 'pending') return 'question-circle-o';
    	if($this->event_approval == 'approved') return 'check-circle';
    	if($this->event_approval == 'rejected') return 'minus-circle';
    }
    
    public function datasource() {
    	if($this->datasource_id <= 0) return null;
    	$ds = TableRegistry::get('Datasource')->find()->where(['source'=>$this->datasource_id])->first();
    	return $ds;
    }
    
    public function getWeight() {
    	$weight = 0;
    	$text = strtolower($this->event_name . " " . $this->place_name . " " . $this->event_description);
    	
		$good = ['synthwave','walpurgis','walpurgisnacht','underground','wgt','electropop','synthpop','depeche','ndh','welle','nachtwerk','ebm','electronic','industrial','industrie','punk','punk rock','folk','electro','edm','electric','minimal','goth','mystik','mystic','mystisch','gothic','wave','ndh','ndw','80s','dark','schwarz','schwarzen','schwarze','mittelalter','mittelalterfestivals','mittelalterfestival','mediaval','indie','alternative','fetish','latex','leder','leather','bondage','morbid','morbide','morbiden','gothrock','rabennacht','synthie','new wave', 'electronic body music', 'synth pop', 'darkfloor', 'darkroom', 'düster', 'schandmaul', 'laibach', 'creatures of the night'];
		$bad = ['psychobilly','schlager','eisenbahnmuseum','ü30','rockparty','cosmetogenesi','marathon','fitness','sports','deathmetal','boogie','glamrock','woodstock','krautrock','deathcore','hardcore','metalcore','grunge','doom','discofox','techhouse','reggae','dancehall','afro','afrobeats','rockabilly','hip','hop','hiphop','soul','blues','house','techno','country','countryrock','cover','covers','heavy','metal','hardrock','rock','karaoke','crossover','mieten','soulstimme','gitarrenmusik','bluesrock','grindcore','noisecore','funk','blues rock','glam rock','70s rock','80s rock','90s rock','slipknot','party hits','beste stimmung','jazz','jazz jam', 'schafkopf', 'black metal', 'lovepop', 'böhse onkelz', 'unterhaltungsmusik', 'rock’n’roll', 'garage-rock', 'kraut-rock', 'chanson'];
		
		foreach($good as $word) {
			if (strpos($text, $word) !== false) $weight++;
		}

		foreach($bad as $word) {
			if (strpos($text, $word) !== false) $weight--;
		}		

    	return $weight;
    }
    
    public function getWeight2() {
    	$weight = 0;
		$good = ['synthwave','walpurgis','walpurgisnacht','underground','wgt','electropop','synthpop','depeche','ndh','welle','nachtwerk','ebm','electronic','industrial','industrie','punk','folk','electro','edm','electric','minimal','goth','mystik','mystic','mystisch','gothic','wave','ndh','ndw','80s','dark','schwarz','schwarzen','schwarze','mittelalter','mittelalterfestivals','mittelalterfestival','mediaval','indie','alternative','fetish','latex','leder','leather','bondage','morbid','morbide','morbiden','nw','gothrock','rabennacht','synthie'];
		$bad = ['psychobilly','schlager','eisenbahnmuseum','ü30','rockparty','cosmetogenesi','marathon','fitness','sports','deathmetal','boogie','glamrock','woodstock','krautrock','deathcore','hardcore','metalcore','grunge','doom','discofox','techhouse','reggae','dancehall','afro','afrobeats','rockabilly','hip','hop','hiphop','soul','blues','house','techno','country','countryrock','cover','covers','heavy','metal','hardrock','rock','karaoke','crossover','mieten','soulstimme','gGitarrenmusik','bluesrock','grindcore','noisecore','funk'];

    	$words = explode(" ", preg_replace('/[\r\n\/\-\s_#+~\*\?,\.\!]+/', ' ', strtolower(trim($this->event_name . " " . $this->place_name . " " . $this->event_description))));
		foreach($words as $word) {
			if(in_array(trim($word), $good)) {
				$weight+=1.2;
			}
			if(in_array(trim($word), $bad)) {
				$weight--;
			}
		}
		return (int)$weight;
    }
    
    public function fromRaw($event) {
		$this->id = $event['id'];
		$this->datasource_id = !empty($event['datasource']['source']) ? $event['datasource']['source'] : $event['datasource'];//;$ds->source;
		$this->event_description = empty($event['description']) ? "" : $event['description'];
		$this->event_name = $event['name'];
		$this->event_approval = 'pending';
		$this->event_start = $event['start_time']->format("Y-m-d H:i:s");
		$this->event_end = empty($event['end_time']) ? "" : $event['end_time']->format("Y-m-d H:i:s");
		$this->owner_id = empty($event['owner']) ? "" : $event['owner']['id'];
		$this->owner_name = empty($event['owner']) ? "" : $event['owner']['name'];
		if(!$this->datasource_id && $this->owner_id) $this->datasource_id = $this->owner_id;
		if(!empty($event['place']) && !empty($event['place']['id'])) {
			$this->place_id = $event['place']['id'];
			$this->place_name = $event['place']['name'];
			if(isset($event['place']['location'])) {
				$this->loc_city = @$event['place']['location']['city'];
				$this->loc_country = @$event['place']['location']['country'];
				$this->loc_street = @$event['place']['location']['street'] ? $event['place']['location']['street'] : "";
				$this->loc_zip = @$event['place']['location']['zip'];
				$this->loc_latitude = @$event['place']['location']['latitude'];
				$this->loc_longitude = @$event['place']['location']['longitude'];
			}
		} else {
			$task = new SyncTask();
			$fb = $task->getFacebook();
			$access_token = $task->getAccessToken();
			try {
				$response = $fb->get("/".$this->datasource_id."/?fields=location,name", $access_token);
				
				$place = $response->getDecodedBody();
				$this->place_id = $place['id'];
				if(empty($this->place_name)) $this->place_name = $place['name'];
					if(isset($place['location'])) {
						$this->loc_city = $place['location']['city'];
						$this->loc_country = @$place['location']['country'];
						$this->loc_street = @$place['location']['street'];
						$this->loc_zip = @$place['location']['zip'];
						$this->loc_latitude = @$place['location']['latitude'];
						$this->loc_longitude = @$place['location']['longitude'];
					}
				} catch(\Exception $e) {
			}
		}
		if($this->loc_zip == null) $this->loc_zip = 0;
    }
    
    public function is_location_ok() {
    	if(!$this->loc_country || !$this->loc_latitude || !$this->loc_longitude)
    		return false;
    		
    	if(!strcasecmp($this->loc_country, "United Kingdom"))
    		return false;

    	// greater europe
    	if($this->loc_longitude > -14 && $this->loc_longitude < 33
    	&& $this->loc_latitude > 34 && $this->loc_latitude < 60)
    		return true;

    	return false;
    }
    
    /*public function cover() {
    	// 1007735706037644?fields=cover
		$task = new SyncTask();
		$fb = $task->getFacebook();
		$access_token = $task->getAccessToken();
		$response = $fb->get("/".$this->id."/?fields=cover", $access_token);
		$cover = $response->getDecodedBody();
		return @$cover['cover']['source'];
    }*/
    
    public function htmlify($text) {
    	$text = preg_replace("/([^\/](www))/i", 'http://$2', $text);
    	$text = preg_replace("/(((f|ht)tp(s)?:\/\/)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;\/\/=]+)/i", '<a href="$1" target="dpnetext">$1</a>', $text);
    	$text = preg_replace("/([-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;\/\/=]+\@[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;\/\/=]+)/i", '<a href="mailto:$1">$1</a>', $text);
    	return "$text";
    }
}
