<?php
/**
 * Story model. Story entities are saved on the stories table. Each story 
 * corresponds to a game in a given Campaign. 
 * 
 */
class Story extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'stories';
	
	/**
	 * Public constructor.
	 * 
	 * @param $campaign Campaign object of the story to be created.
	 * @param $attributes
	 */
	public function __construct($campaign = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
	
		if($campaign!=null) {
			$this->campaign_id = $campaign->id;
		}
	}
}
