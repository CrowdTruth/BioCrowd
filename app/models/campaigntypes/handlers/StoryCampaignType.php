<?php
/**
 * CampaignTypeHandler for QuantityCampaignType. 
 */
class StoryCampaignType extends CampaignTypeHandler {

	/**
	 * See CampaignTypeHandler
	 */
	public function getName() {
		return 'Story';
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
		$extraInfo['label'] = $inputs['label'];
		$extraInfo['story1'] = [];
		$extraInfo['story1'] = $inputs['story1'];
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
		$testvariable = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaignId)->first(['number_performed']);
		
		if(count($testvariable) < 1){
			$numberPerformed = 0;
		} else {
			//Find out what the next game is for this user in this campaign
			$numberPerformed = $testvariable['number_performed'];
		}
		
		//subtract one game_array length from numberPerformed untill the number fits in the game_array. This is to determine the game that is to be played
		while($numberPerformed >= count($game_array)){
			$numberPerformed -= count($game_array);
		}
		
		$gameId = $game_array[$numberPerformed];
		
		//Retrieve the array of story_id's that this campaign entails
		$crude_story_id_array = CampaignStories::where('campaign_id',$campaignId)->select('story_id')->get()->toArray();
		$story_id_array = array_column($crude_story_id_array, 'story_id');
		
		//retrieve the array of stories that this campaign entails
		$story_array = [];
		foreach($story_id_array as $story_id){
			array_push($story_array,Story::where('id', $story_id)->first());
		}
		
		//Select the correct story for this game_id and campaign_id combination from CampaignStories
		$story = $story_array[$numberPerformed];
		
		//Select the correct label for the text under the image
		$tempResponseLabel = unserialize(Story::where('id',$story->id)->first()['extraInfo']);
		
		$responseLabel[0] = $tempResponseLabel['label'];
		$responseLabel[1] = $tempResponseLabel['label1'];
		$responseLabel[2] = $tempResponseLabel['label2'];
		$responseLabel[3] = $tempResponseLabel['label3'];
		$responseLabel[4] = $tempResponseLabel['label4'];
		$responseLabel[5] = $tempResponseLabel['label5'];
		
		//Put the next consecutive game in the game variable
		$game = Game::find($gameId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
		
		//build the view with all extra info that is in the "extraInfo" column of the campaign_games table
		$view = $handler->getView($game);
		
		$view = $view->with('campaignMode', true);
		//$view = $view->with('gameOrigin', false);
		$view = $view->with('story', $story->story_string);
		if(isset($responseLabel) && $responseLabel != null){
			$view = $view->with('responseLabel', $responseLabel); //to overwrite any responselabel of the non-campaignMode game
		}
		$view = $view->with('campaignIdArray', [$campaignId]); //campaignIdArray should contain all campaignId's of all campaigns of which the progress should be updated. 
		return $view;
	}
	
	/**
	 * See GameTypeHandler
	 * The $gameOrigin variable indicates if the user comes from the game menu or the campaign menu. 
	 * This is important because we need to redirect to the correct menu after the response is processed. 
	 * The $done variable indicates if there are more responses to be processed or not. 
	 * If this is the last response to be processed, the $done variable is true and we need to redirect to the correct menu after the response is processed. 
	 */
	public function processResponse($campaign,$gameOrigin,$done,$game) {
		//get the currently played campaign id. If it's not there, it's null.
		$currentlyPlayedCampaignId = Input::get('currentlyPlayedCampaignId');
		$currentlyPlayedCampaign = Campaign::find($currentlyPlayedCampaignId);
		
		//get the flag. If this game was skipped, don't update the campaign progress.
		$flag = Input::get('flag');
		
		//only update the campaign progress if the player came here from the campaign menu and selected this campaign specifically and if this game was not skipped. 
		if(!$gameOrigin && $currentlyPlayedCampaign->name == 'StoryCampaignType' && $flag != "skipped"){
			$this->updateCampaignProgress($campaign,$game);
		}
		//Count the amount of games in this campaign for determinating if the user has finished this campaign. If the user has finished it, the user should be redirected to the campaign menu. 
		$amountOfGamesInThisCampaign = count(CampaignGames::where('campaign_id', $campaign->id)->get());
		if($done){//only redirect if there are no other responses that need processing
			if($gameOrigin && $done){ //The user game from the game menu, so redirect to the game menu. 
				//this should never happen with StoryCampaignTypes
				return Redirect::to(Lang::get('gamelabels.gameUrl'));
			} else { //the user didn't come from the game menu, so figure out whether to go to the next game in this campaign or to the campaign menu
				//Retrieve the array of games that this campaign entails
				$crude_game_array = CampaignGames::where('campaign_id',$campaign->id)->select('game_id')->get()->toArray();
				$game_array = array_column($crude_game_array, 'game_id');
				
				//Find out if this campaign is in the campaign_progress table and if that entry has this user_id. If not: Just give the first gameId in the game_array.
				$testvariable = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->id)->first(['number_performed']);
				
				if(count($testvariable) < 1){
					$numberPerformed = 0;
				} else {
					//Find out what the next game is for this user in this campaign
					$numberPerformed = $testvariable['number_performed'];
				}
				
				while($numberPerformed > count($game_array)){
					$numberPerformed -= count($game_array);
				}
				//return to next campaign or campaign overview page if the campaign is done.
				if($numberPerformed == $amountOfGamesInThisCampaign){
					//go to the campaign menu
					return Redirect::to('campaignMenu');
				} else {
					//go to the next game in this campaign
					return Redirect::to('playCampaign?campaignId='.$currentlyPlayedCampaignId);
				}
			}
		}
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