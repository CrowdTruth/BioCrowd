<?php

class Task extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tasks';

	
	public function __construct($taskType, $data, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		$this->task_type = $taskType;
		$this->data = $data;
	}
}
