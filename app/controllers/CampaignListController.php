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
			->select('campaigns.id as campaignId','campaigns.name as name','image',DB::raw('count(*) as nCampains'))
			->get();
		
		//set the enabled variable to default false
		$enabled = false;

		// Build list to return to user
		/*$levelN = [];
		$levels = [ ];
		
		$campaignNumber = 0;
		
		$highestLevel = $campaignsAvl[count($campaignsAvl)-1]->level;
		
		//loop through all levels
		for($currentLevel = 1; $currentLevel <= $highestLevel; $currentLevel++){
			//and loop through all campaigns for this level
			while($campaignNumber < count($campaignsAvl)){
				//set the campaign variable
				$campaign = $campaignsAvl[$campaignNumber];
				//if the level of this campaign is not equal to the level of the last campaign, push the levelN array to the Levels array and start a new levelN array.
				if($campaign->level != $currentLevel){
					array_push($levels, $levelN);
					$levelN = [];
					break 1;
				} else {
					//attempt to extract the campaignProgress dictionary from the database. If the length is 0, the number of performed games is zero. 
					$campaignProgress = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->campaignId)->first(['number_performed']);
					
					if(count($campaignProgress) < 1){
						$numberPerformed = 0;
					} else {
						//Find out what the next game is for this user in this campaign
						$numberPerformed = $campaignProgress['number_performed'];
					}
					
					$numberOfGamesInThisCampaign = CampaignGames::where('campaign_id',$campaign->campaignId)->count();
					
					//if the progress is less then the total number of campaigns in this campaign, and when the user level is equal to or
					//exceeds the campaign level, set enabled to true. Else, set to false. 
					if(($numberPerformed < $numberOfGamesInThisCampaign) && ($userLevel >= $campaign->level)){
						$enabled = true;
					} else {
						$enabled = false;
					}
					
					//create the campaign pictogram (item) with the correct text and enablement.
					$item = [
					'link' => 'playCampaign?campaignId='.$campaign->campaignId,
					'image' => $campaign->image,
					'text' => $campaign->name,
					'enabled' => $enabled		//disabled if user already completed this campaign or if the user level is not high enough fort this campaign, enabled otherwise
					];
					
					//put the campaign pictogram into the levelN array
					array_push($levelN, $item);
				}
				$campaignNumber++;
			}
		}
		//push the last of the levelN arrays to the levels array
		array_push($levels, $levelN);*/
		
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
