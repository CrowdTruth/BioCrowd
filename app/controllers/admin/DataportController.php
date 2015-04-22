<?php

/**
 * This Controller handles traffic for the admin data import/export.
 */
class DataportController extends BaseController {
	/**
	 * Construct view for exporting judgements to file.
	 */
	public function exportToFileView() {
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
	public function exportToFile() {
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
	public function webhookView() {
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
	public function webhookUpdate() {
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
			
		try {
			// TODO: How many judgments we send? and how often?
			$payload = static::fetchJudgements();
			
			// SHA1 of payload alone, since we don't have API key
			$signature = sha1(print_r($payload, true));
		
			$query = [
					'signal' => 'new_judgments',
					'payload' => $payload,
					'signature' => $signature
			];
			$res = $client->post($webhook, ['query' => $query]);
			$json = $res->json();
			return [
				'status'  => 'ok',
				'message' => 'Webhook successfully called. Response: '.$json['message'],
			];
		} catch (Exception $e) {
			return [
				'status'  => 'error',
				'message' => 'Invalid response from webhook: '.$e,
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
		 SELECT games.id as game_id, level, name as game_name, tasks.data as task_data,
		 user_id, judgements.created_at as created_at, response
		 FROM games
		 INNER JOIN game_has_task ON (games.id=game_has_task.game_id)
		 INNER JOIN tasks         ON (game_has_task.task_id=tasks.id)
		 INNER JOIN judgements    ON (tasks.id=judgements.task_id)
		 WHERE games.id IN ( 1, 2, 3);'
		 */
		$query = DB::table('games')
			->join('game_has_task', 'games.id', '=', 'game_has_task.game_id')
			->join('tasks', 'game_has_task.task_id', '=', 'tasks.id')
			->join('judgements', 'tasks.id', '=', 'judgements.task_id');
		
		if( ! empty($gameIds)) {
			$query = $query->whereIn('games.id', $gameIds);
		}
		
		if( ! empty($dates)) {
			$query = $query->where('judgements.created_at', '>', $dates[0]);
			$query = $query->where('judgements.created_at', '<', $dates[1]);
		}
		
		$query = $query->select('judgements.id as judgment_id','games.id as game_id', 
				'level', 'name as game_name', 'tasks.data as task_data', 'user_id', 
				'judgements.created_at as created_at', 'response');
		$resultSet = $query->get();
		
		// Copy result of query to an array because query returns stdClass objects
		$data = [];
		foreach($resultSet as $row) {
			$cleanRow = [];
			foreach(get_object_vars($row) as $name=>$val) {
				$cleanRow[$name] = $row->$name;
			}
			array_push($data, $cleanRow);
		}
		
		return $data;
	}
}
