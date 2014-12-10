<?php
/**
 * This controller controlls traffic for listing games in the platform.
 */
class GameListController extends GameController {
	
	/**
	 * Display blade view with listing of games.
	 * The view is configured depending on the games which are available
	 * to the user, depending on his level, which games he has previously 
	 * completed, etc.
	 */
	public function listGames() {
		// SQL Query:
		// SELECT games.name,level,handler_class,thumbnail,count(*) as nTasks 
		//		FROM tasks INNER JOIN games ON (tasks.game_id=games.id) 
		//		INNER JOIN game_types ON (games.game_type=game_types.id)
		//		GROUP BY game_id;

		$gamesAvl = DB::table('tasks')
			->join('games', 'tasks.game_id', '=', 'games.id')
			->join('game_types', 'games.game_type', '=', 'game_types.id')
			->groupBy('game_id')
			->orderBy('level')
			->select('games.id as gameId','games.name as name','level','handler_class','thumbnail',DB::raw('count(*) as nTasks'))
			->get();

		// Build list to return to user
		$currLevel = 1;
		$levelN = [];
		$levels = [ ];
		foreach($gamesAvl as $game) {
			if($game->level>$currLevel && count($levelN)>0) {
				$currLevel = $game->level;
				array_push($levels, $levelN);
				$levelN = [];
			}
			
			$item = [ 
				'link' => 'playGame?gameId='.$game->gameId,
				'image' => $game->thumbnail,
				'text' => $game->name,
				'enabled' => true		// TODO: get from DB
			];
			array_push($levelN, $item);
		}
		if(count($levelN)>0) {
			array_push($levels, $levelN);
		}
		
		return View::make('gameMenu')->with('levels', $levels);
	}
}
