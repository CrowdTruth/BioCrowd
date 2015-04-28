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
		$campaignIdArray = Input::get('campaignIdArray');
		$campaign = Campaign::find($campaignIdArray);
		
		// Use corresponding game controller to display game.
		$handlerClass = $campaign->campaignType->handler_class;
		$handler = new $handlerClass();
		return $handler->getView($campaign);
	}
	
	//TO DO: Make an "editCampaign function
	public function submitCampaign(){
		$this->submitGame();
		
		$gameOrigin = false;
		//global $numberPerformed;
		
		$campaignIdArray = unserialize(Input::get('campaignIdArray'));
		$gameOrigin = Input::get('gameOrigin');
		//$numberPerformed = 0;
		
		if(count($campaignIdArray)<=1){
			$campaignId = $campaignIdArray;
			$campaign = Campaign::where('id',$campaignId)->first();
			// Use corresponding campaign controller to process request.
			$handlerClass = $campaign->campaignType->handler_class;
			$handler = new $handlerClass();
			return $handler->processResponse($campaign,$gameOrigin);
			//$this->updateCampaignProgress($campaign);
			
			//get the game_array from the POST data
			//$amountOfGamesInThisCampaign = Input::Get('amountOfGamesInThisCampaign');
			//$amountOfGamesInThisCampaign = count(CampaignGames::where('campaign_id', $campaign->id)->get()->toArray());
		} else {
			foreach($campaignIdArray as $campaignId){
				$campaign = Campaign::where('id',$campaignId)->first();
				// Use corresponding campaign controller to process request.
				$handlerClass = $campaign->campaignType->handler_class;
				$handler = new $handlerClass();
				return $handler->processResponse($campaign,$gameOrigin);
				//$this->updateCampaignProgress($campaign);
				
				//get the game_array from the POST data
				//$amountOfGamesInThisCampaign = Input::Get('amountOfGamesInThisCampaign');
				//$amountOfGamesInThisCampaign = count(CampaignGames::where('campaign_id', $campaign->id)->get()->toArray());
			}
		}
		
		//if the user came here from a game instead of a campaign, redirect to the game menu
		/*if($gameOrigin){
			return Redirect::to('gameMenu');
		} else {
			//return to next cammpaign or campaign overview page if the campaign is done. 
			if($numberPerformed+1 == $amountOfGamesInThisCampaign){
				return Redirect::to('campaignMenu');
			} else {
				return Redirect::to('playCampaign?campaignIdArray='.$campaign->id);
			}
		}*/
	}
	
	/*function updateCampaignProgress($campaign){
		$userId = Auth::user()->get()->id;
		//get the amount of tasks performed by this user
		$campaignProgress1 = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->id)->first(['number_performed']);
		//$campaignProgress1 = Jugement::where('user_id',Auth::user()->get()->id)->where('');
		global $numberPerformed;
		if(count($campaignProgress1) < 1){
			$numberPerformed = 0;
		} else {
			//Find out what the next game is for this user in this campaign
			$numberPerformed = $campaignProgress1['number_performed'];
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
	}*/
}
