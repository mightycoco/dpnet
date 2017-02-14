<?php
namespace App\Controller\Helper;

use Cake\Core\Configure;
use Cake\Network\Http\Client;

class SlackPushConnector
{
	/*
	statusCode=$(curl \
        --write-out %{http_code} \
        --silent \
        --output /dev/null \
        -X POST \
        -H 'Content-type: application/json' \
        --data "${payLoad}" \
        ${slackUrl})
	*/
	public function push($text, $icon, $name, $channel) {
		$url = Configure::read('Slack.incomingHook.' . $channel);
		
		$data['channel'] = $channel;
		$data['username'] = $name;
		$data['icon_emoji'] = $icon;
		$data['text'] = $text;
		
		$http = new Client();
		$response = $http->post(
		    $url['uri'],
		    json_encode($data),
		    ['headers' => ['Content-Type' => 'application/json']]
		);

		return $response->getStatusCode();
	}
}