<?php
/**
 * User Action model. Actions done by users are saved on the user_actions table.
 * Each action entity has a userId for the user which did the action,
 * and a description of the action that was done.
 */
class UserAction extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_actions';

	/**
	 * Public constructor.
	 *
	 * @param $attributes
	 */
	public function __construct($attributes = [])  {
		parent::__construct($attributes); // Eloquent
	}
}
