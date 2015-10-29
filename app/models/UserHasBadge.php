<?php
/**
 * UserHasBadge model. UserHasBadge entities are saved on the user_has_badge table.
 * UserHasBadge keeps track of which users have which Badges
 */
class UserHasBadge extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_has_badge';

	/**
	 * Public constructor.
	 *
	 * @param $attributes
	 */
	public function __construct($attributes = [])  {
		parent::__construct($attributes); // Eloquent
	}
}
