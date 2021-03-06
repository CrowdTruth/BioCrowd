<?php 


/**
 * Campaign model. Campaigns are saved on the campaigns table. A campaign represents the a series of tasks. 
 */
class CampaignGames extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'campaign_has_game';

	/**
	 * Create a new Campaign instance.
	 *
	 * @param $name Name of the campaign.
	 * @param $task_array Tasks that make this campaign
	 * @param $image Name of the image of the badge which is earned when this campaign is done
	 * @param $attributes
	 */
	public function __construct($campaign = null, $game = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		
		if($campaign!=null) {
			$this->campaign_id = $campaign->id;
		}
		
		if($game!=null) {
			$this->game_id = $game->id;
		}
	}
}