<?php
/**
 * This abstract class defines the methods that a handler for a TaskType must 
 * implement. Every TaskType should have an associated handler which extends the 
 * TaskTypeHandler class.
 */
abstract class TaskTypeHandler {
	/**
	 * Returns the name of this handler.
	 */
	public abstract function getName();
	
	/**
	 * Returns the description of this handler.
	 */
	public abstract function getDescription();
	
	/**
	 * Returns the URL of the image used by this handler when being displayed.
	 */
	public abstract function getThumbnail();
	
	/**
	 * Returns the HTML used to fill the 'extra_info' field on the admin interface 
	 * for the GameType corresponding to this handler.
	 * 
	 * @param $extraInfo Existing 'extra_info' used to fill the HTML field 
	 * 		(can be null for a blank DIV).
	 */
	public abstract function getExtrasDiv($extraInfo);
	
	/**
	 * Extract information posted back from the extrasDIV in the admin interface 
	 * and convert it to a string format that can be saved on the database.
	 * 
	 * @param $inputs List of inputs posted from the admin interface (see format 
	 * 		used by Input::all()). 
	 */
	public abstract function parseExtraInfo($inputs);
	
	/**
	 * Return the HTML used to display a Task from the GameType managed by this handler,
	 * on the admin interface.
	 * 
	 * @param $task Task object to be displayed.
	 * 		
	 */
	public abstract function renderTask($task);
	
	/**
	 * Return true if the given data is on a format acceptable for a Task of the 
	 * TaskType managed by this handler.
	 * 
	 * @param $data Task data to be validated.
	 * 
	 * @return true if data is in a valid format, false if it is not.
	 */
	public abstract function validateData($data);
	
	/**
	 * Process the response submitted from the Blade view for given Task object,
	 * of the TaskType managed by this handler. A Judgement should be created by 
	 * this method.
	 * 
	 * @param $task Task which produced the Blade view being processed.
	 */
	public abstract function processResponse($task);
	
	/**
	 * Returns the String representation of this handler.
	 * 
	 * @return multitype:NULL
	 */
	public function __toString() {
		return [ 'HandlerName' => $this->getName() ,
				'description'	=> $this->getDescription()
		];
	}
}
