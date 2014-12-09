<?php
abstract class GameTypeHandler {
	public abstract function getName();
	public abstract function getDescription();
	
	// ADD THESE
	public abstract function getExtrasDiv($extraInfo);	// TODO: Document !
	public abstract function parseExtraInfo($inputs);	// TODO: Document !
	public abstract function renderTask($task);			// TODO: Document !
	public abstract function validateData($data);			// TODO: Document !
	
	
	public abstract function getThumbnail();	// Image used on game list
	public abstract function getView($game);	// BLADE view displaying the game logic for given GAME object
	public abstract function processResponse($game);	// process response from given GAME object
	public function __toString() {
		return [ 'HandlerName' => $this->getName() ,
				'description'	=> $this->getDescription()
			];
	}
}
