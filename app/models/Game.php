<?php
class Game extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'games';
	public $timestamps = false;
	
	public function __construct($gameType = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
	
		if($gameType!=null) {
			$this->game_type = $gameType->id;
		}
	}

	public function gameType() {
		// TODO: this could be more ELOQUENT, but for the moment, I can't do it
		return GameType::where('id', '=', $this->game_type)->first();
	}
}
