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
	
	public function submitCampaign(){
		$this->submitGame();
		
		$campaignIdArray = unserialize(Input::get('campaignIdArray'));
		$gameOrigin = Input::get('gameOrigin',false);
		
		if(count($campaignIdArray)<=1){
			$campaignId = $campaignIdArray;
			$campaign = Campaign::where('id',$campaignId)->first();
			//set a boolean for if the campaignIdArray is longer then 1, and thus has to loop through all campaignId's. 
			//This is important because the handler should have a way of telling if it should redirect to any page yet. 
			$done = true;
			// Use corresponding campaign controller to process request.
			$handlerClass = $campaign->campaignType->handler_class;
			$handler = new $handlerClass();
			return $handler->processResponse($campaign,$gameOrigin,$done);
		} else {
			$counter = 1;
			foreach($campaignIdArray as $campaignId){
				$campaign = Campaign::where('id',$campaignId)->first();
				//set a boolean for if the campaignIdArray is longer then 1, and thus has to loop through all campaignId's.
				//This is important because the handler should have a way of telling if it should redirect to any page yet.
				$done = false;
				if($counter == count($campaignIdArray)){
					$done = true;
				}
				// Use corresponding campaign controller to process request.
				$handlerClass = $campaign->campaignType->handler_class;
				$handler = new $handlerClass();
				$counter += 1;
				//if the loop has reached the last campaignIdArray, return the result of the processResponse function of the handler. Else, keep the loop going. 
				if($done){
					return $handler->processResponse($campaign,$gameOrigin,$done);
				} else {
					$handler->processResponse($campaign,$gameOrigin,$done);
				}
			}
		}
	}
}
