<?php
/**
 * This controller controlls traffic for displaying campaign mechanics
 * and handling responses from these mechanics.
 */
class CampaignController extends BaseController {
	/**
	 * Determine next game in this campaign identified by the given campaignId,
	 * the userId and the campaign_progress
	 */
	public function playCampaign() {
		// Get parameter campaignId
		$capaignId = Input::get('campaignId');
		
		//Find out what the next game is for this user in this campaign
		//TO DO: make this dynamic
		$gameId = 2;
		
		//Put the next consecutive game in the game variable
		$game = Game::find($gameId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
		return $handler->getView($game); //TO DO: Display campaign progress in Games if the user can use the game for a campaign (Do we want that or do they have to have clicked the campaign to work on it?)
	}
}
