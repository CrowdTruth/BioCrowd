<?php
abstract class TaskTypeHandler {
	public abstract function getName();
	public abstract function getDescription();
	public abstract function getDataDiv();		// Return a string with HTML for the DIV which handles CREATE DATA
	public abstract function parseInputs($inputs);		// Handle inputs captured by DIV -- return $data
	
	public function __toString() {
		return [ 'HandlerName' => $this->getName() ,
				'description'	=> $this->getDescription()
			];
	}
}
