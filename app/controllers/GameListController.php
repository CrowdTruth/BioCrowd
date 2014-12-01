<?php
class GameListController extends GameController {

	public function listGames() {
		// LOAD ALL THESE FROM DATABASE , depending on what user has already done.
		
		// SQL Query:
		// SELECT name,level,handler_class,thumbnail,count(*) as nTasks 
		//		FROM tasks INNER JOIN games ON (tasks.game_id=games.id) 
		//		INNER JOIN game_types ON (games.game_type=game_types.id)
		//		GROUP BY game_id;

		// TODO: Fetch task_id of tasks this user has already given judgements for
		
		$userId = Auth::user()->get()->id;
		$userCompletedTasks = [ -1 ];

		$gamesAvl = DB::table('tasks')
			->join('games', 'tasks.game_id', '=', 'games.id')
			->join('game_types', 'games.game_type', '=', 'game_types.id')
			->groupBy('game_id')
			->orderBy('level')
			->select('games.id as gameId','name','level','handler_class','thumbnail',DB::raw('count(*) as nTasks'))
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
				// 'link' => 'playGame?game=CellExController',	// TODO: use game_id from database
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

		/*$level1 = [
						[
								'link' => 'playGame?game=CellExController',
								'image' => 'img/factor_validation1.png',
								'text' => 'Cell extraction',
								'enabled' => true
						]
		];
		$level2 = [];
		$level3 = [];
		$level4 = [];
		$level5 = [];
		$level6 = [];
		$levels = [ $level1, $level2, $level3, $level4, $level5, $level6 ]; */
		return View::make('gameMenu')->with('levels', $levels);
	}
}
