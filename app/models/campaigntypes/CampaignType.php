<?php
/**
 * CampaignType model. CampaignTypes are stored on the campaign_types. A CampaignType describes 
 * a particular mechanism to motivate players to do the tasks/games you want them to do. 
 * Each CampaignType has a reference to a CampaignTypeHandler class 
 * which implements the actual game mechanics.
 * 
 * Additionally, a CampaignType has the following properties:
 * 	name - Name of the CampaignType
 * 	description - Brief explanation of how the campaign type works.
 * 	thumbnail - URL of the picture used to display this campaign type.
 */
class CampaignType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'campaign_types';
	public $timestamps = false;

	/**
	 * Create a new CampaignType object, using the given CampaignTypeHandler as its handler.
	 * 
	 * @param $campaignTypeHandler A CampaignTypeHandler object.
	 * @param $attributes
	 */
	public function __construct($campaignTypeHandler = null, $extraInfo = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		
		if($campaignTypeHandler!=null) {
			$this->name = $campaignTypeHandler->getName();
			$this->description = $campaignTypeHandler->getDescription();
			$this->handler_class = get_class($campaignTypeHandler);
			$this->thumbnail = $campaignTypeHandler->getThumbnail();
		}
	}
}
