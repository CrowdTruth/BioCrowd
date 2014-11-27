<?php

class Task extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tasks';
	
	public function taskType() {
		// TODO: This might be better with an Eloquent belongsTo, but I can't get it to work...
		return TaskType::where('id', '=', $this->task_type)->first();
	}
}
