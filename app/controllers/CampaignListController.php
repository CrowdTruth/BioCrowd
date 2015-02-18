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
			
			$item = [ 
				'link' => 'playCampaign?campaignId='.$campaign->campaignId,
				'image' => $campaign->image,
				'text' => $campaign->name,
				'enabled' => true		// TODO: get from DB
			];
			array_push($levelN, $item);
		}
		if(count($levelN)>0) {
			array_push($levels, $levelN);
		}
		
		return View::make('campaignMenu')->with('levels', $levels);
	}
}
