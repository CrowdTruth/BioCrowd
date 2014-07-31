<?php
class GameController extends BaseController {

	/**
	 * Instantiate a new GameController instance.
	 */
	public function __construct() {
		// All game actions require authentication
		$this->beforeFilter('auth');
	}
	
	public function playGame() {
		// Get parameter which game ?
		$controller = Input::get('game');
		// Validate game exists (else return to game list)
		// Forward request to corresponding game controller.
		return call_user_func($controller.'::getView');
	}
	
	public function submitGame() {
		// Get parameter which game ?
		$controller = Input::get('controller');
		// Validate controller (else return to game list)
		// Forward request to corresponding game controller
		return call_user_func($controller.'::submitGame');
	}
}
