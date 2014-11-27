<?php

class TaskType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'task_types';
	public $timestamps = false;

	public function __construct($taskTypeHandler = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		
		if($taskTypeHandler!=null) {
			$this->name = $taskTypeHandler->getName();
			$this->description = $taskTypeHandler->getDescription();
		}
	}
}
