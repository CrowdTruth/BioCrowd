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
		// Get parameter which campaign ?
		$campaignId = Input::get('campaignId');
		$campaign = Campaign::find($campaignId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $campaign->campaignType->handler_class;
		$handler = new $handlerClass();
		return $handler->getView($campaign);
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
