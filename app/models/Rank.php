<?php
/**
 * Level model. Level entities are saved on the levels table. 
 * Each level has a maximum score and if that is reached, 
 * the user should go up a level. 
 */
class Rank extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ranks';
	
	/**
	 * Public constructor.
	 * 
	 * @param $attributes
	 */
	public function __construct($attributes = [])  {
		parent::__construct($attributes); // Eloquent
	}
}
