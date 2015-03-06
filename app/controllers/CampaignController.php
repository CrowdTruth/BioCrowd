<?php
/**
 * This controller controlls traffic for displaying campaign mechanics
 * and handling responses from these mechanics.
 */
class CampaignController extends GameController {
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
		$view = $view->with('responseLabel', $responseLabel); //to overwrite any responselabel of the non-campaignMode game
		$view = $view->with('campaignId', $campaignId);
		$view = $view->with('numberPerformed', $numberPerformed);
		$view = $view->with('amountOfGamesInThisCampaign', count($game_array));
		return $view;
	}
	
	//TO DO: Make an "editCampaign function
	public function submitCampaign(){
		$this->submitGame();
		
		//get the campaignId parameter
		$campaignId = Input::get('campaignId');
		//get the amount of tasks performed by this user
		$numberPerformed = Input::get('numberPerformed');
		$userId = Auth::user()->get()->id;
		
		if($numberPerformed == 0){
			//Since there is no entry in the campaign_progress table yet, make a new campaignProgress model. 
			$campaignProgress = new CampaignProgress;
			//fill it with all important information
			$campaignProgress->number_performed = $numberPerformed+1;
			$campaignProgress->campaign_id = $campaignId;
			$campaignProgress->user_id = $userId;
			//and save it to the database
			$campaignProgress->save();
		} else {
			//get the campaignProgress entry you need to edit
			$campaignProgress = CampaignProgress::where('user_id',$userId)->where('campaign_id',$campaignId)->first();
			//edit the number_performed in the campaignProgress model and save to the database
			$campaignProgress->number_performed = $numberPerformed+1;
			$campaignProgress->save();
		}
		
		//get the game_array from the POST data
		$amountOfGamesInThisCampaign = Input::Get('amountOfGamesInThisCampaign');
		
		//return to next cammpaign or campaign overview page if the campaign is done. 
		if($numberPerformed+1 == $amountOfGamesInThisCampaign){
			return Redirect::to('campaignMenu');
		} else {
			return Redirect::to('playCampaign?campaignId='.$campaignId);
		}
	}
}
