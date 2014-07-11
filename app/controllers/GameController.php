<?php
class GameController extends BaseController {

	/**
	 * Instantiate a new GameController instance.
	 */
	public function __construct() {
		// All game actions require authentication
		$this->beforeFilter('auth');
	}
}
