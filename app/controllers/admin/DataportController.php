<?php

/**
 * This Controller handles traffic for the admin data import/export.
 */
class DataportController extends BaseController {
	public function __construct() {
		$this->beforeFilter('adminauth');
	}
	
	/**
	 * Construct view for exporting judgements to file.
	 */
	public function getToFile() {
		$games = Game::all();
		$displayGames = [];
		
		foreach($games as $game) {
			array_push($displayGames, [
				'id'	=> $game->id,
				'name' 	=> $game->name,
			]);
		}
		return View::make('admin.exportToFile')
			->with('games', $displayGames);
	}
	
	/**
	 * Export judgements from selected game ids to CSV.
	 */
	public function postToFile() {
		$gameIds = Input::get('games');
	
		$data = $this->fetchJudgements($gameIds);
	
		$status = 200;
		$header = ['Content-type' => 'application/csv'];
		// CSV --> mnshankar\CSV\CSVServiceProvider
		return Response::make(CSV::fromArray($data)
				->render('judgments.csv'), $status, $header);
	}
	
	/**
	 * Return view for updating webhook.
	 */
	public function getWebhook() {
		$webhook = Config::get('webhook.URL');
		return View::make('admin.webhookSetup')
			->with('webhook', $webhook);
	}
	
	/**
	 * Update webhook. This performs one of two actions:
	 * 
	 *   update   Update the webhook call address.
	 *   call     Call the webhook (see callWebhook method).
	 */
	public function postWebhook() {
		$action = Input::get('action');
		
		if($action=='update') {
			$webhook = Input::get('webhook');
			Config::write('webhook.URL', $webhook);

			return Redirect::back()
				->with('flash_message', 'Webhook successfully updated');
		} else if($action=='call') {
			$response = static::callWebhook();
			
			if($response['status']=='ok') {
				return Redirect::back()
					->with('flash_message', $response['message']);
			} else {
				return Redirect::back()
					->with('flash_error', $response['message']);
			}
		}
	}
	
	/**
	 * Call to webhook. 
	 * 
	 * Webhook calls are designed as specified by: 
	 * https://success.crowdflower.com/hc/en-us/articles/201856249-CrowdFlower-API-Webhooks
	 * 
	 * The client sends a JSON request with:
	 * 
	 * 		signal      new_judgments for new judgments
	 * 		payload     a JSON structure with the judgments
	 * 		signature   SHA1(payload + API_KEY) 
	 * 
	 * Webhook can be called from command line:
	 * 
	 *   php artisan ctWebhook:call
	 * 
	 * @return Array containing the call return status.
	 */
	public static function callWebhook() {
		$client = new GuzzleHttp\Client();
		$webhook = Config::get('webhook.URL');
		$chunksize = Config::get('webhook.chunksize');
		$formData = [];
			
		try {
			// TODO: How many judgments we send? and how often?
			$payload = static::fetchJudgements();
			$jsonmsg = "URL: ".$webhook;
			$numberOfJudgementsProcessed = 0;
			$chunkedPayload = array_chunk($payload,$chunksize,1);
			
			foreach($chunkedPayload as $iteration => $chunk){
				// SHA1 of payload alone, since we don't have API key
				$signature = sha1(print_r($chunk, true));
				$formData = [
						'signal' => 'new_judgments',
						'payload' => $chunk,
						'signature' => $signature
				];
				
				//$res = $client->post($webhook, ['body' => $query]);
				//$json = $res->json();
				
				// TODO: must be a better way to convert response to array ?
				$resRaw = $client->post($webhook, ['form_params' => $formData]);
				$resBody = $resRaw->getBody()->__toString();
				$json = json_decode($resBody);
				
				if(isset($json)){
					if($json->status == 'ok'){
						$numberOfJudgementsProcessed += $json->message;
					} else {
						$jsonmsg .= "\nJson error message: ".$json->message;
					}
				} else {
				    $jsonmsg .= "\nJson is empty in chunk ".$chunk;
				    $jsonmsg .= "\nSignature: ".$signature;
				    $jsonmsg .= "\nChunksize: ".count($chunk);
				}
			}
			
			//If the number of processed judgements is not equal to the total number of judgements that was sent in the payload, 
			//something went wrong and the user is notified. 
			if($numberOfJudgementsProcessed == count($payload)){
				$jsonmsg .= "\nAll ".$numberOfJudgementsProcessed." judgements have successfully processed. ";
			} else {
				$jsonmsg .= "\nOnly ".$numberOfJudgementsProcessed." judgements have successfully processed. There are some that failed. ";
			}
			
			if(count($chunkedPayload)<1){
				$jsonmsg .= "No judgements found. ";
			}
			
			return [
				'status'  => 'ok',
				'message' => 'Webhook successfully called. Response: '.$jsonmsg,
			];
		} catch (Exception $e) {
			return [
				'status'  => 'error',
				'message' => 'Invalid response from webhook: '.$e,
				'URL'	=> $webhook, 
				'query' => $formData
			];
		}
	}
	
	/**
	 * Fetch judgement data for exporting as CSV or webhook usage.
	 * 
	 * @param $gameIds  Limit data to a given set of game ID's
	 * @param $dates    Limit data to a given date range
	 * @return Array of judgement arrays.
	 */
	private static function fetchJudgements($gameIds=[], $dates=[]) {
		/**
		SELECT 
		  judgements.id as judgment_id, judgements.game_id as game_id, games.level as level, 
		  games.name as game_name, tasks.data as task_data,
		  judgements.user_id as user_id, judgements.created_at as created_at, judgements.response as response
		FROM judgements
		INNER JOIN games ON (games.id=judgements.game_id)
		INNER JOIN tasks ON (tasks.id=judgements.task_id)
		WHERE games.id IN ( 1, 2, 3);
		 */
		$query = DB::table('judgements')
			->join('games', 'games.id', '=', 'judgements.game_id')
			->join('tasks', 'tasks.id', '=', 'judgements.task_id');
		if( ! empty($gameIds)) {
			$query = $query->whereIn('games.id', $gameIds);
		}
		
		if( ! empty($dates)) {
			$query = $query->where('judgements.created_at', '>', $dates[0]);
			$query = $query->where('judgements.created_at', '<', $dates[1]);
		}
		
		$query = $query->select('judgements.id as judgment_id','judgements.flag as judgment_flag','games.id as game_id','games.game_type_id as game_type_id', 
				'level', 'name as game_name', 'tasks.data as task_data', 'user_id', 'tasks.unit_id as unit_id',
				'judgements.created_at as created_at','judgements.updated_at as updated_at', 'response');
		
		$resultSet = $query->get();
		
		// Copy result of query to an array because query returns stdClass objects
		$data = [];
		foreach($resultSet as $row) {
			$cleanRow = [];
			foreach(get_object_vars($row) as $name=>$val) {
				$cleanRow[$name] = $row->$name;
			}
			
			// Use corresponding game controller to process request.
			$handlerClass = Game::find($cleanRow['game_id'])->gameType->handler_class;
			$handler = new $handlerClass();
			
			$cleanRow['response'] = $handler->decodeJudgement($cleanRow['response']);
			//loop through the cleanRow['response']
			foreach($cleanRow['response'] as $key => $value){
				if($value == "" || (is_array($value) && static::isArrayEmpty($value)) ){
					// if the value is an empty string or an empty array, make sure this key still remains in the array. 
					$cleanRow['response'][$key] = "";
				}
			}
			$cleanRow['game_type_name'] = Game::find($cleanRow['game_id'])->gameType->name;
			
			array_push($data, $cleanRow);
		}
		return $data;
	}
	
	public static function isArrayEmpty($array){
		//set the result variable within this function to make sure that it's saved even when going into itself once more. 
		//just returning static::isArrayEmpty($value) doesn't cut it because the scope is wrong: it will just keep running the first
		//loop of the array that the array was in and not return what the recursive instance of the function returned. 
		$result = true;
		foreach($array as $key => $value){
			if(is_array($value)){
				$result = static::isArrayEmpty($value);
			} else if ($value != "" || $value != null){
				$result = false;
			}
		}
		return $result;
	}
}
