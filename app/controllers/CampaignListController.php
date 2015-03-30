<?php
/**
 * This controller controlls traffic for listing campaigns in the platform.
 */
class CampaignListController extends CampaignController {
	
	/**
	 * Display blade view with listing of campaigns.
	 * The view is configured depending on the campaigns which are available
	 * to the user, depending on his level, which campaigns he has previously 
	 * completed, etc.
	 */
	public function listCampaigns() {
		// SQL Query:
		// SELECT campaigns.id,campaigns.name,level,image,count(*) as nCampaigns
		//		GROUP BY campaign_id;
		//		ORDER BY level;

		$campaigns = DB::table('campaigns')
			->groupBy('id')
			->orderBy('endDate')
			->select('campaigns.id as campaignId','campaigns.name as name','campaigns.badgeName as badgeName','image',DB::raw('count(*) as nCampains'))
			->get();
		
		//set the enabled variable to default false
		$enabled = false;
		
		$campaignsByEndDate = [];
		
		foreach($campaigns as $campaign){
			//attempt to extract the campaignProgress dictionary from the database. If the length is 0, the number of performed games is zero.
			$campaignProgress = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->campaignId)->first(['number_performed']);
				
			if(count($campaignProgress) < 1){
				$numberPerformed = 0;
			} else {
				//Find out what the next game is for this user in this campaign
				$numberPerformed = $campaignProgress['number_performed'];
			}
				
			$numberOfGamesInThisCampaign = CampaignGames::where('campaign_id',$campaign->campaignId)->count();
				
			//if the progress is less then the total number of games in this campaign, set enabled to true. Else, set to false.
			if($numberPerformed < $numberOfGamesInThisCampaign){
				$enabled = true;
			} else {
				$enabled = false;
			}
				
			//create the campaign pictogram (item) with the correct text and enablement.
			$item = [
			'link' => 'playCampaign?campaignId='.$campaign->campaignId,
			'image' => $campaign->image,
			'text' => $campaign->name,
			'badgeName' => $campaign->badgeName,
			'numberPerformed' => $numberPerformed,
			'numberOfGamesInThisCampaign' => $numberOfGamesInThisCampaign,
			'enabled' => $enabled		//disabled if user already completed this campaign or if the user level is not high enough fort this campaign, enabled otherwise
			];
				
			//put the campaign pictogram into the campaigns array
			array_push($campaignsByEndDate, $item);
		}
		
		return View::make('campaignMenu')->with('campaignsByEndDate', $campaignsByEndDate);
	}
}
