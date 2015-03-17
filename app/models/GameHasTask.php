<?php
/**
 * GameHasTask model. GameHasTasks are stored on the game_has_task. A GameHasTask describes 
 * which tasks are part of a particular game. 
 */
class GameHasTask extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'game_has_task';
	public $timestamps = false;

	/**
	 * Create a new GameHasTask object.
	 * 
	 * @param $attributes
	 */
	public function __construct($attributes = [])  {
		parent::__construct($attributes); // Eloquent
	}
}