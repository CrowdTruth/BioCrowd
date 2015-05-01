<?php
/**
 * This Controller handles traffic for the admin interface.
 */
class GameAdminController extends BaseController {
	public function __construct() {
		$this->beforeFilter('adminauth');
	}
	
	/**
	 * Display Blade view listings all existing games.
	 */
	public function getListGames() {
		// TODO: Add pagination ?
		$games = Game::all();
		$displayGames = [];
		
		foreach($games as $game) {
			array_push($displayGames, [
				'id'	=> $game->id, 
				'type'	=> $game->gameType->name,
				'name' 	=> $game->name,
				'level' => $game->level,
				'tasks' => count($game->tasks),
			]);
		}
		return View::make('admin.listgames')->with('games', $displayGames);
	}
	
	/**
	 * Display Blade view for creating new / edit existing Game.
	 */
	public function getEditGame() {
		$gameId = Input::get('gameId');
		$game = Game::find($gameId);
	
		$gameTypes = [];
		$gameTypeDivs = [];
		$tasks = [];
	
		// New games require all game types
		if(is_null($game)) {
			foreach (GameType::all() as $gameType) {
				$gameTypes[$gameType->id] = $gameType->name;
				
				$handlerClass = $gameType->handler_class;
				$handler = new $handlerClass();
				$gameTypeDivs[$gameType->id] = $handler->getExtrasDiv('');
			}
		} else {
			$gameTypes = [];
			$gameTypeDivs = [];
			$gameType = GameType::find($game->game_type_id);
			
			$gameTypes[$gameType->id] = $gameType->name;
			
			$handlerClass = $gameType->handler_class;
			$handler = new $handlerClass();
			$gameTypeDivs[$gameType->id] = $handler->getExtrasDiv($game->extraInfo);
			
			foreach ($game->tasks as $task) {
				$taskHTML = $handler->renderGame($task);
				array_push($tasks, $taskHTML);
			}
		}
		
		return View::make('admin.editgame')
			->with('game', $game)
			->with('gameTypes', $gameTypes)
			->with('gameTypeDivs', $gameTypeDivs)
			->with('tasks', $tasks);
	}
	
	/**
	 * Process request to create new / update existing Game object.
	 */
	public function postEditGame() {
		$gameId = Input::get('id');
		$gameTypeId = Input::get('game_type');
		$gameType = GameType::find($gameTypeId);
	
		// Validate
		$level = Input::get('level');
		$name = Input::get('name');
		$instructions = Input::get('instructions');
		$tasksData = json_decode(Input::get('tasks'));
		
		$handlerClass = $gameType->handler_class;
		$handler = new $handlerClass();
		
		if($gameId=='') {
			$game = new Game($gameType);
			$okMsg = 'Game successfully created';
				
			$newTasks = $tasksData;
		} else {
			$game = Game::find($gameId);
			$okMsg = 'Game successfully updated';
			
			// Make a list containing only 'data' part of the object
			$existing = $game->tasks->lists('data');
			$newTasks = [];
			foreach($tasksData as $task) {
				if( ! in_array($task, $existing)) {
					array_push($newTasks, $task);
				}
			}
		}
		
		$game->level = $level;
		$game->name = $name;
		$game->game_type_id = $gameTypeId;
		$game->instructions = $instructions;
		$game->extraInfo = $handler->parseExtraInfo(Input::all());
		$game->save();
		
		$taskErr = null;
		foreach($newTasks as $taskData) {
			if($handler->validateData($taskData)) {
				$task = new Task($game, $taskData);
				$task->save();
			} else {
				if(is_null($taskErr)) {
					$taskErr = 'Error creating tasks: '.$taskData;
				} else {
					$taskErr = $taskErr.', '.$taskData;
				}
			}
		}
		
		// Return to list games with success / error messages.
		return Redirect::to('admin/listGames')
			->with('flash_message', $okMsg)
			->with('flash_error', $taskErr);
	}
	
	public function postGameUpload() {
		$infile = Input::file('csvfile');
		$response = $this->parseGameFile($infile);
		return Redirect::back()
			->with('flash_message', $response['status']);
	}
	
	private function parseGameFile($infile) {
		$csvObj = CSV::fromFile($infile->getRealPath(), true);
		$data = $csvObj->toArray();
		
		$prevElem = null;
		$currGame = null;
		$saveObjs = [];
		$saveGameTasks = [];
		// Iterate input CSV file
		foreach ($data as $elem) {
			// New game name --> new game
			if($prevElem==null || ($elem['Name']!=$prevElem['Name'] && $elem['Name']!='')) {
				$currGame = $this->createGame($elem);
				array_push($saveObjs, $currGame);
			}
			// New task for current game
			if($currGame!=null) {
				$currTask = $this->createTask($currGame, $elem);
				array_push($saveObjs, $currTask);
				
				$tmp = [
					'Game' => $currGame,
					'Task' => $currTask
				];
				array_push($saveGameTasks, $tmp);
			}
			$prevElem = $elem;
		}
		
		// Now do the saving
		foreach ($saveObjs as $obj) {
			$obj->save();
		}
		
		foreach ($saveGameTasks as $pair) {
			$gameHasTask = new GameHasTask($pair['Game'], $pair['Task']);
			$gameHasTask->save();
		}
		
		return [ 'status' => 'Success' ];
	}
	
	// TODO: document
	private function createGame($elem) {
		
		$gameType = GameType::where('name','=', $elem['GameType'])->first();
		if($gameType==null) {
			// TODO: valudate gameType
			dd('xxxx');
		}
		
		$game = new Game($gameType);
		$game->level = intval($elem['Level']);
		$game->name = $elem['Name'];
		$game->instructions = $elem['Instructions'];
		
		$colNames = array_keys($elem);
		$extraInfoCols = array_filter($colNames, function($key) {
			return strpos($key, 'Extra info: ')===0;
		});
		$extraInfo = [];
		foreach($extraInfoCols as $colName) {
			$cleanColName = str_replace('Extra info: ', '', $colName);
			if(strlen($elem[$colName])>0) {
				$extraInfo[$cleanColName] = $elem[$colName];
			}
		}
		$game->extraInfo = serialize($extraInfo);
		
		return $game;
	}
	
	// TODO: document
	private function createTask($currGame, $elem) {
		try{
		$gameTypeName = $currGame->gameType->name;
		}catch(Exception $e) {
			dd($currGame);
		}
		
		$taskType = TaskType::where('name', '=', $gameTypeName)->first();
		$data = $elem['Task data'];
		
		$task = new Task($taskType, $data);
		return $task;
	}
	
	/**
	 * Display blade view for listing all available GameTypes.
	 * 
	 * All GameTypeHandlers found on HANDLERS_DIR folder will be listed.
	 * GameTypeHandlers which are already in the database, will be listed as 
	 * 'Installed'. GameTypeHandlers which are not in the database will be 
	 * have a link to enable their installation.
	 */
	public function getListGameTypes($handler=null) {
		if($handler!=null) {
			$this->installGameType($handler);
		}
		
		// Load list of available (in file) tasks
		$HANDLERS_DIR = '../app/models/gametypes/handlers';
		$gameTypeFiles = File::files($HANDLERS_DIR);
		foreach($gameTypeFiles as &$fileName) {
			$fileName = str_replace($HANDLERS_DIR.'/', '', $fileName);
			$fileName = str_replace('.php', '', $fileName);
		}
		
		// Load list of GameTypes in database
		$avlNames = [];
		foreach(GameType::all() as $gameType) {
			$avlNames[$gameType->name] = $gameType;
		}
		
		$allGameTypes = [];
		foreach ($gameTypeFiles as $gameHandlerClass) {
			// Check if $taskType is a valid handler
			if(is_subclass_of($gameHandlerClass, 'GameTypeHandler')) {
				$gameHandler = new $gameHandlerClass();
				$gameTypeName = $gameHandler->getName();
				// Check if $taskType exists in $taskTypesDB
				$allGameTypes[$gameTypeName] = [
					'name' 			=> $gameTypeName,
					'installed' 	=> array_key_exists($gameTypeName, $avlNames),
					'handledFile'	=> $gameHandlerClass
				];
			}
		}
		// TODO: Check if we have a Tasks in DB which do not have files ?
		
		return View::make('admin.listgametypes')->with('gameTypes', $allGameTypes);
	}
	
	/**
	 * Install new GameTypeHandlers of the given handler class.
	 */
	private function installGameType($handlerClass) {
		$handler = new $handlerClass();
		$gameType = new GameType($handler);
		$gameType->save();
		
		// TODO: FIX HACK -- We install a game type and thus a task type
		// So -- do we really need the two ? a Task type should suffice, right ?
		$taskHandlerClass = str_replace('GameType', 'TaskType', $handlerClass);
		$taskHandler = new $taskHandlerClass();
		$taskType = new TaskType($taskHandler);
		$taskType->save();
		
		return Redirect::to('admin/listGameTypes')
			->with('flash_message', 'Game type successfully installed');
	}
}
