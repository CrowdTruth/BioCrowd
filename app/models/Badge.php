<?php
/**
* Badge model. Badge entities are saved on the badges table.
* Badges can ben won in multiple ways, for instance
* by finishing campaigns.
* Badges have a name and a text that goes with it. 
*/
class Badge extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'badges';

	/**
	 * Public constructor.
	 *
	 * @param $attributes
	 */
	public function __construct($campaign = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		
		if($campaign!=null) {
			$this->campaign_id = $campaign->id;
		}
	}
}
