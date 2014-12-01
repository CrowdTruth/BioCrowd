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
		$gameId = Input::get('gameId');
		$game = Game::find($gameId);
		// Validate game exists (else return to game list)
		
		// Forward request to corresponding game controller.
		$handlerClass = $game->gameType()->handler_class;
		// Validate $controller is a GameController class
		$handler = new $handlerClass();
		
		return $handler->getView($game);
	}
	
	public function submitGame() {
		// Get parameter which game ?
		$gameId = Input::get('gameId');
		$game = Game::find($gameId);
		// Forward request to corresponding game controller.
		
		$handlerClass = $game->gameType()->handler_class;
		// Validate $controller is a GameController class
		$handler = new $handlerClass();
		
		return $handler->processResponse($game);
	}
}
