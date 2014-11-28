<?php

class Task extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tasks';
	
	public function __construct($game = null, $data = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent

		if($game!=null) {
			$this->game_id = $game->id;
			$this->data = $data;
		}
	}
	
/*	public function taskType() {
		// TODO: This might be better with an Eloquent belongsTo, but I can't get it to work...
		return TaskType::where('id', '=', $this->task_type)->first();
	}*/
}
