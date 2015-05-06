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
		
		//set campaignIdArray
		$campaignIdArray = $this->isInWhichQuantityCampaigns($game);
		
		//if the game is in any campaigns that the user is eligable for,
		//set campaignMode to true and add a parameter telling the campaignController
		//that the user came from a game, not a campaign.
		if(count($campaignIdArray)>0){
			return $handler->getView($game)->with('campaignMode', true)
			->with('gameOrigin', true)
			->with('campaignIdArray', $campaignIdArray);
		} else {
			return $handler->getView($game)->with('campaignMode', false);
		}
	}
	
	/**
	 * Handle an annotation submitted for the game idenified by the given 
	 * gameId.
	 */
	public function submitGame() {
		// Get parameter which game ?
		$gameId = Input::get('gameId');
		$game = Game::find($gameId);
		$campaignIdArray = unserialize(Input::get('campaignIdArray'));
		
		if($campaignIdArray){
			if(count($campaignIdArray) == 1){
				// Use corresponding game controller to process request.
				$handlerClass = $game->gameType->handler_class;
				$handler = new $handlerClass();
				$handler->processResponse($game,$campaignIdArray);
			} else {
				//if this game is in at least one campaign, process the result for all campaigns it is in. 
				foreach($campaignIdArray as $campaignId){
					// Use corresponding game controller to process request.
					$handlerClass = $game->gameType->handler_class;
					$handler = new $handlerClass();
					$handler->processResponse($game,$campaignId);
				}
			}
		} else {
			$campaignId = null;
			// Use corresponding game controller to process request.
			$handlerClass = $game->gameType->handler_class;
			$handler = new $handlerClass();
			$handler->processResponse($game,$campaignId);
		}
		return Redirect::to('gameMenu');
	}
	
	function isInWhichQuantityCampaigns($game) {
		//if the game is in any campaigns that the user is eligable for,
		//set campaignMode to true and add a parameter telling the campaignController
		//that the user came from a game, not a campaign.
		$crude_campaignIdArray = CampaignGames::where('game_id',$game->id)->select('campaign_id')->get()->toArray();
		//TO DO: make a restriction so that user has to be eligable
		//TO DO: make a restriction so that only QuantityCampaigns are given back
		$campaignIdArray = array_column($crude_campaignIdArray, 'campaign_id');
		return $campaignIdArray;
	}
}
