<?php
/**
 * This abstract class defines the methods that a handler for a GameType must 
 * implement. Every GameType should have an associated handler which extends the 
 * GameTypeHandler class.
 */
abstract class CampaignTypeHandler {
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
