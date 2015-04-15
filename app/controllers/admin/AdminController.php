<?php
/**
 * This Controller handles traffic for the admin interface.
 */
class AdminController extends BaseController {
	/**
	 * Redirect to the Login Blade page.
	 */
	public function requestLogin() {
		return View::make('admin.login');
	}
	
	/**
	 * Perform login action.
	 */
	public function doLogin() {
		$user = Input::get('username');
		$pass  = Input::get('password');
		if( Auth::admin()->attempt( [ 'username' => $user, 'password' => $pass ] )){
			return Redirect::to('admin');
		} else {
			return Redirect::to('admin/login')->with('flash_error', 'Invalid email/password combination.')->withInput();
		}
	}
	
	/**
	 * Perform logout action.
	 */
	public function requestLogout() {
		Session::flush();
		Session::regenerate();
		return Redirect::to('admin/login')->with('flash_error', 'You have been logged out.');
	}
	
	/**
	 * Display admin home screen.
	 */
	public function home() {
		return View::make('admin.home');
	}
	
	/**
	 * Display Blade view for new user creation.
	 */
	public function newUserView() {
		return View::make('admin.newuser');
	}
	
	/**
	 * Display Blade view listing all existing users.
	 */
	public function listUsersView() {
		$users = AdminUser::all();
		return View::make('admin.listuser')->with('users', $users);
	}
	
	/**
	 * Display Blade view listings all existing games.
	 */
	public function listGamesView() {
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
	public function editGameView() {
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
				$taskHTML = $handler->renderTask($task);
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
	public function editGameAction() {
		$gameId = Input::get('id');
		$gameTypeId = Input::get('game_type_id');
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
	
	/**
	 * Display blade view for listing all available GameTypes.
	 * 
	 * All GameTypeHandlers found on HANDLERS_DIR folder will be listed.
	 * GameTypeHandlers which are already in the database, will be listed as 
	 * 'Installed'. GameTypeHandlers which are not in the database will be 
	 * have a link to enable their installation.
	 */
	public function listGameTypesView() {
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
	public function listGameTypesAction() {
		$handlerClass = Input::get('handler');
		$handler = new $handlerClass();
		$gameType = new GameType($handler);
		$gameType->save();
		return Redirect::to('admin/listGameTypes')->with('flash_message', 'Game type successfully installed');
	}
}
