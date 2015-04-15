<?php

/**
 * This Controller handles traffic for the admin data import/export.
 */
class DataportController extends BaseController {
	// TODO: document
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
	
	// TODO: document
	public function exportToFile() {
		$gameIds = Input::get('games');
		/**
			SELECT games.id as game_id, level, name as game_name, tasks.data as task_data, 
					user_id, judgements.created_at as created_at, response 
			FROM games
			INNER JOIN game_has_task ON (games.id=game_has_task.game_id)
			INNER JOIN tasks         ON (game_has_task.task_id=tasks.id)
			INNER JOIN judgements    ON (tasks.id=judgements.task_id)
			WHERE games.id IN ( 1, 2, 3);'
		 */
		$data = DB::table('games')
			->join('game_has_task', 'games.id', '=', 'game_has_task.game_id')
			->join('tasks', 'game_has_task.task_id', '=', 'tasks.id')
			->join('judgements', 'tasks.id', '=', 'judgements.task_id')
			->whereIn('games.id', $gameIds)
			->select('games.id as game_id', 'level', 'name as game_name', 'tasks.data as task_data',
					'user_id', 'judgements.created_at as created_at', 'response')
			->get();
		// CSV --> mnshankar\CSV\CSVServiceProvider
		return Response::make(CSV::fromArray($data)->render('file.csv'), 200, ['Content-type' => 'application/csv',]);
	}
	
	public function webhook() {
		//TODO: implement
		return 'TODO...';
	}
}
