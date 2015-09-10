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
		$flag = Input::get('flag');
		// Get the userId
		$userId = Auth::user()->get()->id;
		try{
			$campaignIdArray = unserialize(Input::get('campaignIdArray'));
		} catch(Exception $e){
			$campaignIdArray = explode(",", Input::get('campaignIdArray'));
		}
		$consecutiveGame = 'consecutiveGame';
		
		if($campaignIdArray){
			if(count($campaignIdArray) == 1){
				//flatten the campaignIdArray to a campaignId
				$campaignId = $campaignIdArray[0];
				// Use corresponding game controller to process request.
				$handlerClass = $game->gameType->handler_class;
				$handler = new $handlerClass();
				$handler->processResponse($game,$campaignId);
				//If the task was not flagged as skipped, give the user score.
				if(($flag != "skipped") && ($flag != "incomplete")){
					//add the score to the users score column and add the score to the scores table. 
					ScoreController::addScore($game->score,$userId,'You have finished Game '.$game->name.' and received a score of'.$game->score,$gameId);
				}
			} else {
				$handlerClass = $game->gameType->handler_class;
				$handler = new $handlerClass();
				//if this game is in at least one campaign, process the result for all campaigns it is in. 
				foreach($campaignIdArray as $campaignId){
					// Use corresponding game controller to process request.
					$handler->processResponse($game,$campaignId);
				}
				//If the task was not flagged as skipped, give the user score. 
				if(($flag != "skipped") && ($flag != "incomplete")){
					//add the score to the users score column and add the score to the scores table.
					ScoreController::addScore($game->score,$userId,'You have finished Game '.$game->name.' and received a score of'.$game->score,$gameId);
				}
			}
		} else { 
			$campaignId = null;
			// Use corresponding game controller to process request.
			$handlerClass = $game->gameType->handler_class;
			$handler = new $handlerClass();
			$handler->processResponse($game,$campaignId);
			//If the task was not flagged as skipped, give the user score.
			if(($flag != "skipped") && ($flag != "incomplete")){
				//add the score to the users score column and add the score to the scores table.
				ScoreController::addScore($game->score,$userId,'You have finished Game '.$game->name.' and received a score of'.$game->score,$gameId);
			}
		}
		if(($flag != "skipped") && ($flag != "incomplete")){
			return Redirect::to('playGame?gameId='.$gameId)->with('consecutiveGame', $consecutiveGame)->with('score', $game->score);
		} else {
			return Redirect::to('playGame?gameId='.$gameId)->with('flag', $flag);
		}
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
