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
		// SELECT games.tag,level,handler_class,thumbnail,count(*) as nTasks 
		//		FROM tasks INNER JOIN games ON (game_has_task.game_id=games.id) 
		//		INNER JOIN game_types ON (games.game_type_id=game_types.id)
		//		GROUP BY game_id;

		$gamesAvl = DB::table('game_has_task')
			->join('games', 'game_has_task.game_id', '=', 'games.id')
			->join('game_types', 'games.game_type_id', '=', 'game_types.id')
			->groupBy('game_id')
			->orderBy('level')
			->select('games.id as gameId','games.tag as tag','level','handler_class','thumbnail',DB::raw('count(*) as nTasks'))
			->get();
		
		//get the level of the user
		$userLevel = Auth::user()->get()->level;
		//put the enabled variable to default false
		$enabled = false;

		// Build list to return to user
		$levelN = [];
		$levels = [];
		
		$gameNumber = 0;
		
		if(count($gamesAvl)>0) {
			$highestLevel = $gamesAvl[count($gamesAvl)-1]->level;
		} else {
			$highestLevel = 0;
		}
		
		//loop through all levels
		for($currentLevel = 1; $currentLevel <= $highestLevel; $currentLevel++) {
			//and loop through all games, ordered by level
			while($gameNumber < count($gamesAvl)){
				//set the game variable
				$game = $gamesAvl[$gameNumber];
				//if the level of this game is not equal to the level of the last game, push the levelN array to the Levels array and start a new levelN array. 
				if($game->level != $currentLevel){
					//per level, at the end of all games in that level, add a random campaign for that level, if it exists
					$campaign = Campaign::where('name', 'RandomGamesCampaignLevel'.$currentLevel)->first();
					if($campaign){
						$item = [
								'link' => 'playCampaign?campaignId='.$campaign->id,
								'image' => $campaign->image,
								'text' => $campaign->tag,
								'enabled' => $enabled
						];
						array_push($levelN, $item);
					}
					array_push($levels, $levelN);
					$levelN = [];
					break 1;
				} else {
					//Set enabled to true if the user level is equal to or exceeds the game level and false if otherwise.
					if($userLevel >= $game->level){
						$enabled = true;
					} else {
						$enabled = false;
					}
					
					//create the game pictogram (item) with the correct text and enablement. 
					$item = [
					'link' => 'playGame?gameId='.$game->gameId,
					'image' => $game->thumbnail,
					'text' => $game->tag,
					'enabled' => $enabled
					];
					
					//put the game pictogram into the levelN array
					array_push($levelN, $item);
				}
				$gameNumber++;
			}
			
			//If this level is the highest level there is
			if($currentLevel == $highestLevel){
				//put the randomGamesCampaign of the highest level here, if it exists
				$campaign = Campaign::where('name', 'RandomGamesCampaignLevel'.$currentLevel)->first();
				if($campaign){
					$item = [
							'link' => 'playCampaign?campaignId='.$campaign->id,
							'image' => $campaign->image,
							'text' => $campaign->tag,
							'enabled' => $enabled
					];
					array_push($levelN, $item);
				}
			}
		}
		
		//push the last of the levelN arrays to the levels array
		array_push($levels, $levelN);
		
		return View::make('home')->with('levels', $levels);
	}
}
