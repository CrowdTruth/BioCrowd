<?php 


/**
 * Campaign model. Campaigns are saved on the campaigns table. A campaign represents the a series of tasks. 
 */
class Campaign extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'campaigns';

	/**
	 * Create a new Campaign instance.
	 *
	 * @param $name Name of the campaign.
	 * @param $task_array Tasks that make this campaign
	 * @param $image Name of the image of the badge which is earned when this campaign is done
	 * @param $attributes
	 */
	public function __construct($campaignType = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		
		if($campaignType!=null) {
			$this->campaign_type_id = $campaignType->id;
		}
	}

	/**
	 * Return the CampaignType of the current campaign.
	 */
	public function campaignType() {
		return $this->belongsTo('CampaignType', 'campaign_type_id', 'id');
	}
	
	/**
	 * Return a list of Game objects associated with the current campaign.
	 */
	public function games() {
		// return $this->hasMany('Game', 'campaign_id', 'id');
		return $this->belongsToMany('Game', 'campaign_has_game', 'id');
	}
}
