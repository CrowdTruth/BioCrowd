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
	 * See CampaignTypeHandler
	 */
	public function getView($campaign) {
		// Get parameter campaignId
		$campaignId = $campaign->id;
			
		//Retrieve the array of games that this campaign entails
		$crude_game_array = CampaignGames::where('campaign_id',$campaignId)->select('game_id')->get()->toArray();
		$game_array = array_column($crude_game_array, 'game_id');
		
		//select the next gameId in this campaign for this user
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
		$view = $view->with('campaignIdArray', [$campaignId]); //campaignIdArray should contain all campaignId's of all campaigns of which the progress should be updated
		return $view;
	}
	
	/**
	 * See CampaignTypeHandler
	 * The $gameOrigin variable indicates if the user comes from the game menu or the campaign menu. 
	 * This is important because we need to redirect to the correct menu after the response is processed. 
	 * The $done variable indicates if there are more responses to be processed or not. 
	 * If this is the last response to be processed, the $done variable is true and we need to redirect to the correct menu after the response is processed.
	 */
	public function processResponse($campaign,$gameOrigin,$done,$game) {
		//get the currently played campaign id. If it's not there, it's null. 
		$currentlyPlayedCampaignId = Input::get('currentlyPlayedCampaignId');
		//get the flag. If this game was skipped, don't update the campaign progress. 
		$flag = Input::get('flag');
		if($flag != "skipped"){
			//get the tag for this campaign if the campaign has been finished. If no campaign was finished, keep it null.
			$campaignScoreTag = $this->updateCampaignProgress($campaign,$game);
			//Put the campaignScoreTag in the session if it's not null, so that future campaign updates will remember that this campaign was finished.
			if($campaignScoreTag){
				Session::put('campaignScoreTag', $campaignScoreTag);
			}
		}
		
		//Only redirect if $done is true
		if($done){
			//if the user came here from the game menu instead of the campaign menu, redirect to the game the user came from
			//add the campaignScoreTag that was put into the session variable when it exists, so that the score gained is showed in the next game view.
			if($gameOrigin){
				return Redirect::to('playGame?gameId='.$game->id)->with('campaignScoreTag', Session::pull('campaignScoreTag', $campaignScoreTag));
			} else { //if a user came here from the campaign menu, figure out what to redirect to
				$nextGame = $this->selectNextGameInCampaignForThisUser($campaign);
				//return to next cammpaign or campaign overview page if the campaign is done.
				if($nextGame){
					//go to the next game in this campaign. This should always be the case, as the games circulate. 
					return Redirect::to('playCampaign?campaignId='.$currentlyPlayedCampaignId)->with('campaignScoreTag', Session::pull('campaignScoreTag', $campaignScoreTag));
				} else {
					//this should never happen TO DO: make it so that the $nextGame is false if the amount of played games for this campaign
					//divided by the played games for this campaign is equal?
					return Redirect::to('campaignMenu');
				}
			}
		}
	}
	
	/**
	 * Returns the gameId of the game with the lowest amount of judgements of the current user that is in this campaign. 
	 */
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
	 * Check if the user already has the badge for this campaign. 
	 * If not, check if this user has played all games in this campaign the amount of times they should
	 * If so, give them the badge. 
	 */
	function updateCampaignProgress($campaign,$game){
		$userId = Auth::user()->get()->id;
		//get all games in this campaign and how many times this user has finished them
		$allGamesInThisCampaignNTimesPlayed = DB::table('campaign_has_game')
			->leftJoin('judgements','campaign_has_game.game_id','=','judgements.game_id')
			->where('campaign_has_game.campaign_id',$campaign->id)
			->where('judgements.campaign_id',$campaign->id)
			->where('flag','=','')
			->orWhere('flag',null)
			->where('user_id',$userId)
			->orWhere('user_id',null)
			->groupBy('campaign_has_game.game_id')
			->select('campaign_has_game.game_id as GameId',DB::raw('count(judgements.game_id) as nTimes'))
			->get();
		
		//get the amount of games for every game in this campaign
		$allGamesInThisCampaignNTimes = CampaignGames::where('campaign_id',$campaign->id)
			->groupBy('game_id')
			->select('game_id as GameId',DB::raw('count(game_id) as nTimes'))
			->get()
			->toArray();
		
		//make a variable for the amount of times that the user has finished this campaign
		$amountOfTimesFinished = 0;
		
		//set the loop to true to start iteration
		$loop = true;
		
		//keep track of the campaignProgress (#games played in the last iteration of the while loop)
		$numberPerformed = 0;
		
		//subtract all instances of the campaign that were already played from the $allGamesInThisCampaignNTimesPlayed array
		while($loop){
			$numberPerformed = 0;
			for($i=0; $i<(count($allGamesInThisCampaignNTimesPlayed)); $i++){
				for($j=0; $j<(count($allGamesInThisCampaignNTimes)); $j++){
					if($allGamesInThisCampaignNTimes[$j]['GameId'] == $allGamesInThisCampaignNTimesPlayed[$i]->GameId){
						if($allGamesInThisCampaignNTimesPlayed[$i]->nTimes >= $allGamesInThisCampaignNTimes[$j]['nTimes']){
							//if the number of times that this game was played is more then the campaign needed, 
							//subtract the nTimes that this game should be played for this campaign
							$allGamesInThisCampaignNTimesPlayed[$i]->nTimes -= $allGamesInThisCampaignNTimes[$j]['nTimes'];
							$numberPerformed += 1;
						} else {
							//if this game was played less times then this campaign needed, 
							//set $loop to false to stop the iteration. 
							$loop = false;
						}
					}
				}
			}
			if($loop == true){
				$amountOfTimesFinished += 1;
			}
		}
		
		//make an $amountOfTimesFinished_OLD variable for when the campaign progress gets updated and put it to 0 default. 
		$amountOfTimesFinished_OLD = 0;
		
		//Update the campaignProgress table
		//get the amount of games performed by this user for this campaign
		$testvariable = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->id)->first(['number_performed']);
		//if the testvariable is less then 1, the database gave no result and thus the user 
		//didn't have a campaignPerformance for this campaign yet. 
		if(count($testvariable) < 1){
			//Since there is no entry in the campaign_progress table yet, make a new campaignProgress model.
			$campaignProgress = new CampaignProgress;
			//fill it with all important information
			$campaignProgress->number_performed = $numberPerformed;
			$campaignProgress->times_finished = $amountOfTimesFinished;
			$campaignProgress->campaign_id = $campaign->id;
			$campaignProgress->user_id = $userId;
			//and save it to the database
			$campaignProgress->save();
		} else {
			//get the campaignProgress entry you need to edit
			$campaignProgress = CampaignProgress::where('user_id',$userId)->where('campaign_id',$campaign->id)->first();
			//fill the $amountOfTimesFinished_OLD variable before updating it 
			$amountOfTimesFinished_OLD = $campaignProgress->times_finished;
			//edit the number_performed in the campaignProgress model and save to the database
			$campaignProgress->number_performed = $numberPerformed;
			$campaignProgress->times_finished = $amountOfTimesFinished;
			$campaignProgress->save();
		}
		
		//check if the campaign was just finished (every time the user finishes it he will receive score)
		if ($amountOfTimesFinished >= 1 && $amountOfTimesFinished_OLD!=$amountOfTimesFinished) {
			//get which badge can be won for this campaign, if any
			$badgeForThisCampaign = Badge::where('campaign_id',$campaign->id)->get();
			if(count($badgeForThisCampaign) != 0){
				$userHasBadgeForThisCampaign = UserHasBadge::where('user_id',$userId)->where('badge_id',$badgeForThisCampaign[0]->id)->get();
			}
			
			//if the user didn't have a badge for this campaign yet
			if(count($userHasBadgeForThisCampaign) == 0 ){
				//Give the user the badge that belongs to this campaign
				$userHasBadge = new UserHasBadge();
				$userHasBadge->user_id = $userId;
				$userHasBadge->badge_id = $badgeForThisCampaign[0]->id;
				$userHasBadge->save();
			}
			
			//add the score to the users score column and add the score to the scores table.
			ScoreController::addScore($campaign->score,$userId,"You have finished Campaign ".$campaign->tag." and received a score of".$campaign->score,$game->id,$campaign->id);
			return ['campaignScore' => $campaign->score, 'campaignTag' => $campaign->tag];
				
		}
	}
}