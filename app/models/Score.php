<?php
/**
 * Score model. Score entities are saved on the scores table. 
 * Each score entity has a userId for the user which received the score, 
 * the possibility of putting in a gameId for which the score was received, 
 * the possibility of putting in a campaignId for which the score was received, 
 * the score that was gained, 
 * and a description of the way the user gained the score. 
 */
class Score extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'scores';
	
	/**
	 * Public constructor.
	 * 
	 * @param $campaign Campaign object of the story to be created.
	 * @param $attributes
	 */
	public function __construct($attributes = [])  {
		parent::__construct($attributes); // Eloquent
	}
}
