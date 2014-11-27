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

	public function listTaskView() {
		// TODO: Add pagination ?
		$tasks = Task::all();
		$displayTasks = [];
		foreach($tasks as $task) {
			array_push($displayTasks, [ 
				'id' 	=> $task->id,
				'type'	=> $task->taskType()->name,
				'data'	=> $task->data
			]);
		}
		return View::make('admin.listtasks')->with('tasks', $displayTasks);
	}

	public function listTaskAction() {
		// TODO: implement this method
		return 'Implement method listTaskAction...';
	}
	
	public function newTaskView() {
		// TODO: Check user is allowed to create new tasks
		
		// List id=> name pairs for available task types
		$taskTypes = TaskType::all();
		$taskTypesForm = [];
		foreach ($taskTypes as $taskType) {
			$taskTypesForm[$taskType->id] = $taskType->name;
		}
		return View::make('admin.newtask')->with('taskTypes', $taskTypesForm);
	}
	
	public function newTaskAction() {
		// TODO: Check user is allowed to create new tasks
		$taskTypeId = Input::get('taskType');
		$data  = Input::get('data');
		
		$taskType = TaskType::where('id', '=', $taskTypeId)->first();
		
		// TODO: check whether call comes from ADMIN or API
		
		// TODO: Instead of creating a new Task, we should create a
		// new TaskOfParticularType_extends_Task
		// TaskOfParticularType should 'know' how to save $data in its expected format.
		// $task = new Task($taskTypeId, $data);
		// $task->save();

		// TODO: Return error messages on ADMIN or API format
		return Redirect::to('admin')->with('flash_message', 'Task successfully created');
	}

	public function listTaskTypeView() {
		// Load list of available (in file) tasks
		$taskTypeFiles = [ 'CellExTaskType' ];
		
		// Load list of TaskTypes in database
		$avlNames = [];
		foreach(TaskType::all() as $taskType) {
			$avlNames[$taskType->name] = $taskType;
		}
		
		$allTaskTypes = [];
		foreach ($taskTypeFiles as $taskTypeClass) {
			$taskTypeInstance = new $taskTypeClass();
			$taskTypeName = $taskTypeInstance->getName();
			
			// Check if $taskType is a valid TaskType
			if(is_subclass_of($taskTypeInstance, 'TaskTypeHandler')) {
				// Check if $taskType exists in $taskTypesDB
				$allTaskTypes[$taskTypeName] = [
					'name' 			=> $taskTypeName,
					'installed' 	=> array_key_exists($taskTypeName, $avlNames),
					'handledFile'	=> $taskTypeClass
				];
			}
		}
		
		// TODO: Check if we have a Tasks in DB which do not have files ?
		
		// Return list of 
		//  - name -> Name of the task
		//  - installed -> yes/no
		//  - task handledFile -> TaskTypeHandler file for the task
		return View::make('admin.listtasktypes')->with('taskTypes', $allTaskTypes);
	}
	
	public function listTaskTypeAction() {
		$handlerClass = Input::get('handler');
		$handler = new $handlerClass();
		$taskType = new TaskType($handler);
		$taskType->save();
		return Redirect::to('admin')->with('flash_message', 'Task type successfully created');
	}
}
