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
		
		//only update the campaign progress if the player came here from the campaign menu
		if(!$gameOrigin){
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
				//return to next cammpaign or campaign overview page if the campaign is done.
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
		//get the amount of games performed by this user for this campaign
		$testvariable = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->id)->first(['number_performed']);
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
			
			//check the amount of games in this campaign and give the user scoring if the campaign is finished (for the first time??)
			$numberOfGamesInCampaign = count(CampaignGames::where('campaign_id',$campaign->id)->get());
			if ($campaignProgress->number_performed == $numberOfGamesInCampaign) { //should we put it this way or divide number_performed by 4 to give user score every time the user finishes the campaign over and over again?
				//add the score to the users score column and add the score to the scores table.
				ScoreController::addScore($campaign->score,$userId,"You have finished Campaign ".$campaign->tag." and received a score of".$campaign->score,$game->id,$campaign->id);
			}
		}
	}
}