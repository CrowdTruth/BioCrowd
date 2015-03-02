<?php
/**
 * This controller controlls traffic for listing campaigns in the platform.
 */
class CampaignListController extends CampaignController {
	
	/**
	 * Display blade view with listing of campaigns.
	 * The view is configured depending on the games which are available
	 * to the user, depending on his level, which campaigns he has previously 
	 * completed, etc.
	 */
	public function listCampaigns() {
		// SQL Query:
		// SELECT campaigns.name,level,image,count(*) as nTasks 
		//		FROM tasks INNER JOIN games ON (tasks.game_id=games.id) 
		//		GROUP BY campaign_id;

		$campaignsAvl = DB::table('campaigns')
			->groupBy('id')
			->orderBy('level')
			->select('campaigns.id as campaignId','campaigns.name as name','level','image',DB::raw('count(*) as nCampains'))
			->get();

		// Build list to return to user
		$currLevel = 1;
		$levelN = [];
		$levels = [ ];
		foreach($campaignsAvl as $campaign) {
			if($campaign->level>$currLevel && count($levelN)>0) {
				$currLevel = $campaign->level;
				array_push($levels, $levelN);
				$levelN = [];
			}
			
			//attempt to extract the campaignProgress dictionary from the database. If the length is 0, the number of performed games is zero. 
			$campaignProgress = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->campaignId)->first(['number_performed']);
			
			if(count($campaignProgress) < 1){
				$numberPerformed = 0;
			} else {
				//Find out what the next game is for this user in this campaign
				$numberPerformed = $campaignProgress['number_performed'];
			}
			
			$numberOfGamesInThisCampaign = CampaignGames::where('campaign_id',$campaign->campaignId)->count();
			
			//set default completed to false
			$completed = false;
			
			//if the progress is the same as the number of campaigns in this campaign, set completed to true
			if($numberPerformed == $numberOfGamesInThisCampaign){
				$completed = true;
			}
			
			$item = [ 
				'link' => 'playCampaign?campaignId='.$campaign->campaignId,
				'image' => $campaign->image,
				'text' => $campaign->name,
				'enabled' => !$completed		//disabled if user already completed this campaign, enabled otherwise
			];
			array_push($levelN, $item);
		}
		if(count($levelN)>0) {
			array_push($levels, $levelN);
		}
		
		return View::make('campaignMenu')->with('levels', $levels);
	}
}
