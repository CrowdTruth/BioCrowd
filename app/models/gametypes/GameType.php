<?php
/**
 * GameType model. GameTypes are stored on the game_types. A GameType describes 
 * a particular game mechanism (e.g. drawing annotations on an image, answering 
 * a question, etc). Each GameType has a reference to a GameTypeHandler class 
 * which implements the actual game mechanics.
 * 
 * Additionally, a GameType has the following properties:
 * 	name - Name of the GameType
 * 	description - Brief explanation of how the game type works.
 * 	thumbnail - URL of the picture used to display this game type.
 */
class GameType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'game_types';
	public $timestamps = false;

	/**
	 * Create a new GameType object, using the given GameTypeHandler as its handler.
	 * 
	 * @param $gameTypeHandler A GameTypeHandler object.
	 * @param $attributes
	 */
	public function __construct($gameTypeHandler = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
		
		if($gameTypeHandler!=null) {
			$this->name = $gameTypeHandler->getName();
			$this->description = $gameTypeHandler->getDescription();
			$this->handler_class = get_class($gameTypeHandler);
			$this->thumbnail = $gameTypeHandler->getThumbnail();
		}
	}
}
