<?php
/**
 * CampaignStories model. CampaignStories entities are saved on the campaign_has_story table. Each campaignStory 
 * corresponds to a given Story in a given Campaign. 
 * 
 */
class CampaignStories extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'campaign_has_story';
	
	/**
	 * Public constructor.
	 * 
	 * @param $campaign Campaign object of the campaignStory to be created.
	 * @param $campaign Story object of the campaignStory to be created.
	 * @param $attributes
	 */
	public function __construct($campaign = null, $story = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
	
		if($campaign!=null) {
			$this->campaign_id = $campaign->id;
		}
		
		if($story!=null) {
			$this->story_id = $story->id;
		}
	}
}
