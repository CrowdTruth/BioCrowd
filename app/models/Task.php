<?php
/**
 * Task model. Tasks are saved on the tasks table. A task represents the basic 
 * unit of a game (e.g. a single image or piece of text to annotate, a single 
 * question to be answered, etc).
 */
class Task extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tasks';
	
	/**
	 * Create a new Task instance.
	 * 
	 * @param $game Game object which the created Task makes part of.
	 * @param $data Game data used for this task -- the structure of this data 
	 * 		depends on the GameType and thus the GameTypeHandler should ensure 
	 * 		that the data is on a suitable format.
	 * @param $attributes
	 */
	public function __construct($game = null, $data = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent

		if($game!=null) {
			$this->game_id = $game->id;
			$this->data = $data;
		}
	}
}
