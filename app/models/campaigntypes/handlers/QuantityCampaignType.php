<?php
/**
 * CampaignTypeHandler for QuantityCampaignType. 
 */
class QuantityCampaignType extends CampaignTypeHandler {

	/**
	 * See CampaignTypeHandler
	 */
	public function getName() {
		return 'Quantity';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getDescription() {
		return 'Rewarding the user for doing the same game X times';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getExtrasDiv($extraInfo) {
		$extraInfo = unserialize($extraInfo);
		$label = $extraInfo['label'];
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-2 control-label'>Label:</label>";
		$divHTML .= "<input class='form-control' name='quantityCampaignLabel' type='text' value='".$label."' id='quantityCampaignLabel'>";
		$divHTML .= "";
		return $divHTML;
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		$extraInfo['label'] = [];
		$extraInfo['label'] = $inputs['quantityCampaignLabel'];
		return serialize($extraInfo);
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getThumbnail() {
		return 'img/army_mission.png';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getView($campaign) {
		// Get parameter campaignId
		$campaignId = $campaign->id;
			
		//Retrieve the array of games that this campaign entails
		$crude_game_array = CampaignGames::where('campaign_id',$campaignId)->select('game_id')->get()->toArray();
		$game_array = array_column($crude_game_array, 'game_id');
				
				//Find out if this campaign is in the campaign_progress table and if that entry has this user_id. If not: Just give the first gameId in the game_array.
				/*$testvariable = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaignId)->first(['number_performed']);
				
				if(count($testvariable) < 1){
					$numberPerformed = 0;
				} else {
					//Find out what the next game is for this user in this campaign
					$numberPerformed = $testvariable['number_performed'];
				}
				
				//$gameId = $game_array[$numberPerformed];*/   //TO DO: remove this whole section
		$gameId = $this->selectNextGameInCampaignForThisUser($campaign);
		
		//Put the next consecutive game in the game variable
		$game = Game::find($gameId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
		
		//build the view with all extra info that is in the "extraInfo" column of the game model
		$view = $handler->getView($game);
		foreach(unserialize($game['extraInfo']) as $key=>$value){
			$view = $view->with($key, $value);
		}
		$view = $view->with('campaignMode', true);
		//$view = $view->with('gameOrigin', false); //why did we comment this out? No idea...
		if(isset($responseLabel) && $responseLabel != null){
			$view = $view->with('responseLabel', $responseLabel); //to overwrite any responselabel of the non-campaignMode game
		}
		$view = $view->with('campaignIdArray', $campaignId);
		return $view;
	}
	
	/**
	 * See GameTypeHandler
	 * The $gameOrigin variable indicates if the user comes from the game menu or the campaign menu. 
	 * This is important because we need to redirect to the correct menu after the response is processed. 
	 * The $done variable indicates if there are more responses to be processed or not. 
	 * If this is the last response to be processed, the $done variable is true and we need to redirect to the correct menu after the response is processed.
	 */
	public function processResponse($campaign,$gameOrigin,$done) {
		$this->updateCampaignProgress($campaign);
		
		//Only redirect if $done is true
		if($done){
			//if the user came here from the game menu instead of the campaign menu, redirect to the game menu
			if($gameOrigin){
				return Redirect::to('gameMenu');
			} else { //if a user came here from the campaign menu, figure out what to redirect to
				$nextGame = $this->selectNextGameInCampaignForThisUser($campaign);
				//return to next cammpaign or campaign overview page if the campaign is done.
				if($nextGame){
					//go to the next game in this campaign. This should always be the case, as the games circulate. 
					return Redirect::to('playCampaign?campaignIdArray='.$campaign->id);
				} else {
					//this should never happen TO DO: make it so that the $nextGame is false if the amount of played games for this campaign
					//divided by the played games for this campaign is equal?
					return Redirect::to('campaignMenu');
				}
			}
		}
	}
	
	function selectNextGameInCampaignForThisUser($campaign){
		$userId = Auth::user()->get()->id;
		//Retrieve the array of games that this campaign entails
		$crude_game_array = CampaignGames::where('campaign_id',$campaign->id)->select('game_id')->get()->toArray();
		$game_array = array_column($crude_game_array, 'game_id');
		
		//check which game was last played by this user for this campaign
		$lastGamePlayed = Judgement::where('user_id',$userId)->where('campaign_id',$campaign->id)->orderBy('id','DESC')->first(['game_id']);
		
		if(count($lastGamePlayed) < 1){
			//if the user has not played a game in this campaign before, start from scratch in the game array. 
			$nextGame = $game_array[0];
		} else {
			//make a variable to contain all games and their counts done by this user for this campaign
			$totalGamesCountForUserForThisCampaign = [];
			
			//make a table with all judgements of this user of games in this campaign and count the amount of times that a game is played by the user
			$totalGamesCountForUserForThisCampaign =  DB::table('judgements')
			->where('user_id',$userId)
			->where('campaign_id',$campaign->id)
			->groupBy('game_id')
			->select('game_id as GameId',DB::raw('count(*) as nTimes'))  //will it order it on nTimes automatically?
			->get();
			
			//loop through the unique game id's in the game_array and fill in the blanks (nTimes that were not in judgements)
			foreach(array_unique($game_array) as $gameId){
				$theGameIsInTheJudgedGames = $this->findGameIdInCampaign($totalGamesCountForUserForThisCampaign,$gameId);
				
				if($theGameIsInTheJudgedGames == []){
					$theGameIsInTheJudgedGames = false;
				}
				//If it is not in there, put this gameId into $gamesCountNotDoneByUserForThisCampaign with nTimes = 0.
				if(!$theGameIsInTheJudgedGames) {
					//If it is not in there, put this gameId into $gamesCountNotDoneByUserForThisCampaign with nTimes = 0.
					$tempVar = [];
					$tempVar['GameId'] = $gameId;
					$tempVar['nTimes'] = 0;
					$tempVar = (object) $tempVar;
					array_push($totalGamesCountForUserForThisCampaign,$tempVar);
				}
			}

			//get a table with all games in game_array and their count (how many times is this game id in this campaign?)
			$gamesCountForThisCampaign = DB::table('campaign_has_game')
			->where('campaign_id',$campaign->id)
			->groupBy('game_id')
			->select('game_id as GameId', DB::raw('count(*) as nTimes'))
			->get();
			
			//divide each count of $totalGamesCountForUserForThisCampaign by the amount of times this game is in the game_array
			foreach($totalGamesCountForUserForThisCampaign as $gameCount){
				//Extract the current gameCount['nTimes'] from the $gameCount
				$oldGameCount = $gameCount->nTimes;
				//find out how many times this GameId has been played in this campaign
				$gameIdAndTimesInThisCampaign = $this->findGameIdInCampaign($gamesCountForThisCampaign, $gameCount->GameId);
				//Put that many times into $timesInThisCampaign
				$timesInThisCampaign = $gameIdAndTimesInThisCampaign[0]->nTimes;
				//calculate the newGameCount
				$newGameCount = $oldGameCount/$timesInThisCampaign;
				//put the newGameCount in the place of the old one
				$gameCount->nTimes = $newGameCount;
			}
			
			//see which game is next (select the lowest count in the $totalGamesCountForUserForThisCampaign)
			$lowestGameCount = $totalGamesCountForUserForThisCampaign[0];
			foreach($totalGamesCountForUserForThisCampaign as $gameCount){
				if($gameCount->nTimes < $lowestGameCount->nTimes){
					$lowestGameCount = $gameCount;
				}
			}
			$nextGame = $lowestGameCount->GameId;
		}
		return $nextGame;
	}
	
	/**
	 * Searches and returns a given value out of a given array containing stdObjects with keys and values. 
	 */
	function findGameIdInCampaign($array, $value){
		$results = array();
		foreach($array as $stdObj) {
			if($stdObj->GameId == $value) {
				array_push($results, $stdObj);
			}
		}
		return $results;
	}
	
	/**
	 * Returns an array of tasks that are in the given game. 
	 */
	function tasksInGame($gameId){		
		$crudeTasksArray = GameHasTask::where('game_id',$gameId)->select('task_id')->get()->toArray();
		$tasksArray = array_column($crudeTasksArray,'task_id');
		return $tasksArray;
	}
	
	/**
	 * Selects the next task for a user. 
	 * The next task is the task that has been done the least amount of times by this user 
	 * and with the lowest id in the tasks table. 
	 */
	function selectNextTaskForUser($userId){	
		//get the list of tasks that this user has done untill now
		$tasksDoneByUser = $this->getTaskListForUser($userId);
		
		//Get a list of all tasks excluding the ones done by this user
		$totalTasksCountNotDoneByUser = DB::table('tasks')
		->whereNotIn('id',$tasksDoneByUser)
		->select('id as TaskId')
		->get();
		
		//For each of the tasks in totalTasksCountNotDoneByUser,
		//add a second colomn "nTasks" and fill it with 0
		foreach($totalTasksCountNotDoneByUser as $taskCount){
			$taskCount->nTimes = 0;
		}
		
		//get the task count done by this user
		$tasksCountDoneByUser = $this->getTasksCountDoneByUser($userId);
		
		$totalTasksCountForUser = [];
		//Now add $tasksCountDoneByUser to $totalTasksCountNotDoneByUser
		array_push($totalTasksCountForUser,$totalTasksCountNotDoneByUser,$tasksCountDoneByUser);
		
		//Now use totalTasksCountForUser to select the next task that has been done the least amount of times by this user (select the top TaskId in the list)
		//and return the id of the next task for this user
		return $totalTasksCountForUser[0]->TaskId;
	}
	
	/**
	 * Returns a list of taskId's that have been done by the given userId. 
	 */
	function getTaskListForUser($userId){
		$tasksCountDoneByUser = $this->getTasksCountDoneByUser($userId);
		//Make an array with all tasks that were done by this user (without the count)
		$tasksDoneByUser;
		for($i = 0; $i < count($tasksCountDoneByUser); $i++){
			$tasksDoneByUser[$i] = $tasksCountDoneByUser[$i]->TaskId;
		}
		return $tasksDoneByUser;
	}
	
	/**
	 * Returns a list of taskId's that have been done by the given userId 
	 * and the amount of times they have been finished by this userId. 
	 */
	function getTasksCountDoneByUser($userId){
		//select task_id from judgements
		//where user_id = currentUserId
		//group by task_id
		//raw count the result and put this in an array (result is an array with 2 columns: TaskId and number of times the current user performed this task)
		$tasksCountDoneByUser = DB::table('judgements')
		->where('user_id',$userId)
		->groupBy('task_id')
		->select('task_id as TaskId',DB::raw('count(*) as nTimes'))
		->get();
		return $tasksCountDoneByUser;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function renderCampaign($game) {
		return "";
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function validateData($data) {
		return "";
	}
	
	/**
	 * Check if this user has made progress on this campaign before. 
	 * If not make a new entry in the campaignProgress table. 
	 * If yes, edit the entry in the campaignProgress table: up the numberPerformed by 1. 
	 */
	function updateCampaignProgress($campaign){
		$userId = Auth::user()->get()->id;
		//get the amount of tasks performed by this user
		$testvariable = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->id)->first(['number_performed']);
		//$campaignProgress1 = Jugement::where('user_id',Auth::user()->get()->id)->where('');
		global $numberPerformed;
		if(count($testvariable) < 1){
			$numberPerformed = 0;
		} else {
			//Find out what the next game is for this user in this campaign
			$numberPerformed = $testvariable['number_performed'];
		}
			
		if($numberPerformed == 0){
			//Since there is no entry in the campaign_progress table yet, make a new campaignProgress model.
			$campaignProgress = new CampaignProgress;
			//fill it with all important information
			$campaignProgress->number_performed = $numberPerformed+1;
			$campaignProgress->campaign_id = $campaign->id;
			$campaignProgress->user_id = $userId;
			//and save it to the database
			$campaignProgress->save();
		} else {
			//get the campaignProgress entry you need to edit
			$campaignProgress = CampaignProgress::where('user_id',$userId)->where('campaign_id',$campaign->id)->first();
			//edit the number_performed in the campaignProgress model and save to the database
			$campaignProgress->number_performed = $numberPerformed+1;
			$campaignProgress->save();
		}
	}
}