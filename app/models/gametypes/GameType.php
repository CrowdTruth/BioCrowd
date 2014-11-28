<?php
class GameType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'game_types';
	public $timestamps = false;

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
