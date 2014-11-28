<?php
class AdminController extends BaseController {
	/**
	 * Redirect to the Login page.
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
		if( Auth::admin()->attempt( array('username' => $user, 'password' => $pass) )){
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
	 * Redirect to admin home screen.
	 */
	public function home() {
		return View::make('admin.home');
	}

	public function newUserView() {
		return View::make('admin.newuser');
	}
	
	public function newUserAction() {
		// TODO: implement this method
		return 'Implement method newUserAction...';
	}

	public function listUsersView() {
		$users = AdminUser::all();
		return View::make('admin.listuser')->with('users', $users);
	}

	public function listUsersAction() {
		// TODO: implement this method
		return 'Implement method listUsersAction...';
	}

	public function listGamesView() {
		// TODO: Add pagination ?
		$games = Game::all();
		$displayGames = [];
		foreach($games as $game) {
			array_push($displayGames, [ 
				'id' 	=> $game->id,
				'type'	=> $game->gameType()->name,
			]);
		}
		return View::make('admin.listgames')->with('games', $displayGames);
	}

	public function listTaskAction() {
		// TODO: implement this method
		return 'Implement method listTaskAction...';
	}
	
	public function newTaskView() {
		// TODO: Check user is allowed to create new tasks
		
		// List id=> name pairs for available task types
		$taskTypes = TaskType::all();
		
		$ttIdName = [];
		$ttIdDiv = [];
		foreach ($taskTypes as $taskType) {
			$taskTypeClass = $taskType->handler_class;
			$taskTypeHandler = new $taskTypeClass();
			
			$ttIdName[$taskType->id] = $taskType->name;
			$ttIdDiv[$taskType->id] = $taskTypeHandler->getDataDiv();
		}
		return View::make('admin.newtask')
				->with('taskTypesNames', $ttIdName)
				->with('taskTypesDivs', $ttIdDiv);
	}
	
	public function newTaskAction() {
		// TODO: Check user is allowed to create new tasks
		$taskTypeId = Input::get('taskType');
		$data  = Input::get('data');
		
		$taskType = TaskType::where('id', '=', $taskTypeId)->first();
		
		// TODO: check whether call comes from ADMIN or API
		
		$taskTypeClass = $taskType->handler_class;
		$taskTypeHandler = new $taskTypeClass();
		$data = $taskTypeHandler->parseInputs(Input::all());
		$task = new Task($taskType, $data);
		$task->save();
		
		// TODO: Return error messages on ADMIN or API format
		return Redirect::to('admin')->with('flash_message', 'Task successfully created');
	}

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
			// Check if $taskType is a valid TaskType
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
		
		// Return list of 
		//  - name -> Name of the task
		//  - installed -> yes/no
		//  - task handledFile -> TaskTypeHandler file for the task
		return View::make('admin.listgametypes')->with('gameTypes', $allGameTypes);
	}
	
	public function listGameTypesAction() {
		$handlerClass = Input::get('handler');
		$handler = new $handlerClass();
		$gameType = new GameType($handler);
		$gameType->save();
		return Redirect::to('admin/listGameTypes')->with('flash_message', 'Game type successfully installed');
	}
}
