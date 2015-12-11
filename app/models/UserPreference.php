<?php
/**
 * UserPreference model. UserPreference entities are saved on the user_preferences table.
 * UserPreference can be set on the Profile page
 */
class UserPreference extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_preferences';

	/**
	 * Public constructor.
	 *
	 * @param $attributes
	 */
	public function __construct($attributes = [])  {
		parent::__construct($attributes); // Eloquent
	}
}
