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
		$responseLabel = unserialize(Story::where('id',$story->id)->first()['extraInfo'])['label'];
		
		//Put the next consecutive game in the game variable
		$game = Game::find($gameId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
		
		//build the view with all extra info that is in the "extraInfo" column of the campaign_games table
		$view = $handler->getView($game);

		//foreach(unserialize($campaignGame['extraInfo']) as $key=>$value){
		//	$view = $view->with($key, $value);								TO DO: should this stay?
		//}
		
		$view = $view->with('campaignMode', true);
		//$view = $view->with('gameOrigin', false);
		$view = $view->with('story', $story->story_string);
		if(isset($responseLabel) && $responseLabel != null){
			$view = $view->with('responseLabel', $responseLabel); //to overwrite any responselabel of the non-campaignMode game
		}
		$view = $view->with('campaignIdArray', $campaignId);
		return $view;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function processResponse($campaign,$gameOrigin) {
		$this->updateCampaignProgress($campaign);
		//if the user came here from a game instead of a campaign, redirect to the game menu
		$amountOfGamesInThisCampaign = count(CampaignGames::where('campaign_id', $campaign->id)->get());
		if($gameOrigin){
			return Redirect::to('gameMenu');
		} else {
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
				return Redirect::to('campaignMenu');
			} else {
				return Redirect::to('playCampaign?campaignIdArray='.$campaign->id);
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