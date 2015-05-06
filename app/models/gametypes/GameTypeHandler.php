<?php
/**
 * This abstract class defines the methods that a handler for a GameType must 
 * implement. Every GameType should have an associated handler which extends the 
 * GameTypeHandler class.
 */
abstract class GameTypeHandler {
	/**
	 * Returns the name of this handler.
	 */
	public abstract function getName();
	
	/**
	 * Returns the description of this handler.
	 */
	public abstract function getDescription();
	
	/**
	 * Returns the URL of the image used by this handler when being displayed.
	 * on ListGames.
	 */
	public abstract function getThumbnail();
	
	/**
	 * Returns the HTML used to fill the 'extra_info' field on the admin interface 
	 * for the GameType corresponding to this handler.
	 * 
	 * @param $extraInfo Existing 'extra_info' used to fill the HTML field 
	 * 		(can be null for a blank DIV).
	 */
	public abstract function getExtrasDiv($extraInfo);
	
	/**
	 * Extract information posted back from the extrasDIV in the admin interface 
	 * and convert it to a string format that can be saved on the database.
	 * 
	 * @param $inputs List of inputs posted from the admin interface (see format 
	 * 		used by Input::all()). 
	 */
	public abstract function parseExtraInfo($inputs);
	
	/**
	 * Return the HTML used to display a Game from the GameType managed by this handler,
	 * on the admin interface.
	 * 
	 * @param $game Game object to be displayed.
	 * 		
	 */
	public abstract function renderGame($game);
	
	/**
	 * Return true if the given data is on a format acceptable for a Game of the 
	 * GameType managed by this handler.
	 * 
	 * @param $data Game data to be validated.
	 * 
	 * @return true if data is in a valid format, false if it is not.
	 */
	public abstract function validateData($data);
	
	/**
	 * Generate a Blade view used for displaying the game logic for given Game object,
	 * of the GameType managed by this handler.
	 * 
	 * @param $game The Game to be displayed.
	 * 
	 * @return A blade View object displaying the given game.
	 */
	public abstract function getView($game);
	
	/**
	 * Process the response submitted from the Blade view for given Game object,
	 * of the GameType managed by this handler. A Judgement should be created by 
	 * this method.
	 * 
	 * @param $game Game which produced the Blade view being processed.
	 */
	public abstract function processResponse($game,$campaignId);
	
	/**
	 * Take judgement submitted by user and encode it as a String for storage 
	 * in the database. This operation should be inverted by decodeJudgement.
	 * 
	 * @param $judgement Judgement as submitted (structure is game dependent).
	 * 
	 * @return String encoded form of the judgement.
	 */
	public abstract function encodeJudgement($judgement);
	
	/**
	 * Take a judgement String stored in the database and return a judgement 
	 * as it was submitted by user. This operation is the inverse of encodeJudgement.
	 * 
	 * @param $judgementStr String encoded form of the judgement.
	 * 
	 * @return Judgement as submitted (structure is game dependent).
	 */
	public abstract function decodeJudgement($judgementStr);
	
	/**
	 * Returns the String representation of this handler.
	 * 
	 * @return multitype:NULL
	 */
	public function __toString() {
		return [ 'HandlerName' => $this->getName() ,
				'description'	=> $this->getDescription()
		];
	}
}
