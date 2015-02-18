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
	public function __construct($name = null, $game_array = [], $story = [], $image = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
	}
}