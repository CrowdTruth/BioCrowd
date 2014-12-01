<?php
abstract class GameTypeHandler {
	public abstract function getName();
	public abstract function getDescription();
	public abstract function getDataDiv();		// Return a string with HTML for the DIV which handles CREATE DATA
	public abstract function parseInputs($inputs);		// Handle inputs captured by DIV -- return $data
	public abstract function getThumbnail();	// Image used on game list
	public abstract function getView($game);	// BLADE view displaying the game logic for given GAME object
	public abstract function processResponse($game);	// process response from given GAME object
	public function __toString() {
		return [ 'HandlerName' => $this->getName() ,
				'description'	=> $this->getDescription()
			];
	}
}
