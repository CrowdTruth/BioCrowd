<?php
/**
 * CampaignTypeHandler for QuantityCampaignType. 
 */
class StoryCampaignType extends CampaignTypeHandler {

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
		
		$gameId = $game_array[$numberPerformed];
		
		$campaignGame = CampaignGames::where('campaign_id',$campaignId)->where('game_id',$gameId)->first();
		
		//Select the correct story for this game_id and campaign_id combination from CampaignCames
		$story = $campaignGame['story'];
		
		//Select the correct label for the text under the image
		$responseLabel = unserialize(CampaignGames::where('campaign_id',$campaignId)->where('game_id',$gameId)->first()['extraInfo'])['label'];
		
		//Put the next consecutive game in the game variable
		$game = Game::find($gameId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
		
		//build the view with all extra info that is in the "extraInfo" column of the campaign_games table
		$view = $handler->getView($game);
		foreach(unserialize($campaignGame['extraInfo']) as $key=>$value){
			$view = $view->with($key, $value);
		}
		$view = $view->with('campaignMode', true);
		$view = $view->with('story', $story);
		if(isset($responseLabel) && $responseLabel != null){
			$view = $view->with('responseLabel', $responseLabel); //to overwrite any responselabel of the non-campaignMode game
		}
		$view = $view->with('campaignId', $campaignId);
		$view = $view->with('numberPerformed', $numberPerformed);
		$view = $view->with('amountOfGamesInThisCampaign', count($game_array));
		return $view;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function processResponse($game) {
		return "";
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
}