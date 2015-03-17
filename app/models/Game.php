<?php
/**
 * Game model. Game entities are saved on the games table. Each game 
 * corresponds to a given GameType, and can have a series of Tasks
 * associated with the game.
 * 
 * Game objects also have the following properties:
 *		level, name, instructions and extraInfo.
 */
class Game extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'games';
	public $timestamps = false;
	
	/**
	 * Public constructor.
	 * 
	 * @param $gameType GameType object of the game to be created.
	 * @param $attributes
	 */
	public function __construct($gameType = null, $attributes = [])  {
		parent::__construct($attributes); // Eloquent
	
		if($gameType!=null) {
			$this->game_type = $gameType->id;
		}
	}

	/**
	 * Return the GameType of the current game.
	 */
	public function gameType() {
		return $this->belongsTo('GameType', 'game_type', 'id');
	}
	
	/**
	 * Return a list of Task objects associated with the current game.
	 */
	public function tasks() {
		return $this->hasMany('GameHasTask', 'game_id', 'id');
	}
	
	/**
	 * Return serialized data from the database that is associated with the current game.
	 */
	public function data() {
		$tasks = $this->hasMany('GameHasTask','task_id', 'id');
		$data = [];
		foreach($tasks as $task){
			$newData = $task->data;
			array_push($data, $newData);
		}
		return $data;
	}
}
