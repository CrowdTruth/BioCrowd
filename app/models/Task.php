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
	 * @param unit_id Id of this task in the CrowdTruth database
	 * @param $attributes
	 */
	public function __construct($taskType = null, $data = null, $unit_id = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent

		if($taskType!=null) {
			$this->task_type_id = $taskType->id;
			$this->data = $data;
			$this->unit_id = $unit_id;
		}
	}
}
