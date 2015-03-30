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
	public function __construct($game = null, $task = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		
		if($game!=null) {
			$this->game_id = $game->id;
		}
		
		if($task!=null) {
			$this->task_id = $task->id;
		}
	}
}