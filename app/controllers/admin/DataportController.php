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
		$formData = [];
			
		try {
			// TODO: How many judgments we send? and how often?
			$payload = static::fetchJudgements();
			// SHA1 of payload alone, since we don't have API key
			$signature = sha1(print_r($payload, true));
			$formData = [
					'signal' => 'new_judgments',
					'payload' => $payload,
					'signature' => $signature
			];
			
			//$res = $client->post($webhook, ['body' => $query]);
			//$json = $res->json();
			
			// TODO: must be a better way to convert response to array ?
			$resRaw = $client->post($webhook, ['form_params' => $formData]);
			$resBody = $resRaw->getBody()->__toString();
			$json = json_decode($resBody);

			return [
				'status'  => 'ok',
				'message' => 'Webhook successfully called. Response: '.$json->message,
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
				'level', 'name as game_name', 'tasks.data as task_data', 'user_id', 
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
			$cleanRow['game_type_name'] = Game::find($cleanRow['game_id'])->gameType->name;
			
			array_push($data, $cleanRow);
		}
		
		return $data;
	}
}
