<?php
/**
 * This controller controlls traffic for displaying game mechanics
 * and handling responses from these mechanics.
 */
class GameController extends BaseController {

	/**
	 * Instantiate a new GameController instance.
	 */
	public function __construct() {
		// All game actions require authentication
		$this->beforeFilter('auth');
	}
	
	/**
	 * Display game identified by the given gameId
	 */
	public function playGame() {
		// Get parameter which game ?
		$gameId = Input::get('gameId');
		$game = Game::find($gameId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
		return $handler->getView($game)->with('campaignMode', false);
	}
	
	/**
	 * Handle an annotation submitted for the game idenified by the given 
	 * gameId.
	 */
	public function submitGame() {
		// Get parameter which game ?
		$gameId = Input::get('gameId');
		$game = Game::find($gameId);
		
		// Use corresponding game controller to process request.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
		return $handler->processResponse($game);
	}
}
