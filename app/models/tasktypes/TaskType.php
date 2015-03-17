<?php
/**
 * TaskType model. TaskTypes are stored on the task_types. A TaskType describes 
 * a particular game mechanism (e.g. drawing annotations on an image, answering 
 * a question, etc). Each TaskType has a reference to a TaskTypeHandler class 
 * which implements the actual task mechanics.
 * 
 * Additionally, a TaskType has the following properties:
 * 	name - Name of the TaskType
 * 	description - Brief explanation of how the game type works.
 * 	thumbnail - URL of the picture used to display this game type.
 */
class TaskType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'task_types';
	public $timestamps = false;

	/**
	 * Create a new TaskType object, using the given TaskTypeHandler as its handler.
	 * 
	 * @param $taskTypeHandler A TaskTypeHandler object.
	 * @param $attributes
	 */
	public function __construct($taskTypeHandler = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		
		if($taskTypeHandler!=null) {
			$this->name = $taskTypeHandler->getName();
			$this->description = $taskTypeHandler->getDescription();
			$this->handler_class = get_class($taskTypeHandler);
			$this->thumbnail = $taskTypeHandler->getThumbnail();
		}
	}
}
