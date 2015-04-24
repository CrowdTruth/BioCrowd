<?php

/**
 * CampaignProgress model. Campaign progress is saved on the campaign_progress table. 
 */
class CampaignProgress extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'campaign_progress';

	/**
	 * Create a new CampaignProgress instance.
	 *
	 * @param $campaign_id Id of the campaign.
	 * @param $user_id Id of the user. 
	 * @param $number_performed Number of games performed in this campaign
	 * @param $attributes
	 */
	public function __construct($campaign_id = null, $user_id = null, $number_performed = null, $campaign_has_game_id = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
	}
}