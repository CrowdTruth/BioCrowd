<?php
/**
 * This controller controlls traffic for displaying campaign mechanics
 * and handling responses from these mechanics.
 */
class CampaignController extends BaseController {
	/**
	 * Determine next game in this campaign identified by the given campaignId,
	 * the userId and the campaign_progress
	 */
	
	public function playCampaign() {
		// Get parameter campaignId
		$campaignId = Input::get('campaignId');
		
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
	//	$story = CampaignGames::where('campaign_id',$campaignId)->where('game_id',$gameId)->first()['story'];
		$story = $campaignGame['story'];
		
		//Select the correct label for the text under the image
		$responseLabel = unserialize(CampaignGames::where('campaign_id',$campaignId)->where('game_id',$gameId)->first()['extraInfo'])['label'];
		
		//Put the next consecutive game in the game variable
		$game = Game::find($gameId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
//		return $handler->getView($game)
//			->with('campaignMode', true)
//			->with('story', $story)
//			->with('responseLabel', $responseLabel);

		$view = $handler->getView($game);
		//dd($campaignGame['extraInfo']);
		dd(unserialize($campaignGame['extraInfo']));
		foreach(unserialize($campaignGame['extraInfo']) as $key->$value){
			$view = $view->with($key, $value);
		}
		$view = $view->with('campaignMode', true);
		$view = $view->with('story', $story);
		$view = $view->with('responseLabel', $responseLabel);
		return $view;
	}
	
	//TO DO: Make an "editCampaign function
}
