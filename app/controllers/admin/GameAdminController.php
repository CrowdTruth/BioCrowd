<?php
/**
 * This Controller handles game related tasks for admin interface, including 
 * creating and editing of games and campaigns.
 */
class GameAdminController extends BaseController {
	/**
	 * Initialize controller.
	 */
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
	
	/**
	 * Process post request for game uploading. A game CSV file should be posted. The 
	 * CSV file is then parsed and games are created from it. Afterwards user is 
	 * redirected back with a success/failure message loaded.
	 */
	public function postGameUpload() {
		$infile = Input::file('csvfile');
		$response = $this->parseGameFile($infile->getRealPath());
		return Redirect::back()
			->with('flash_message', $response['status']);
	}
	
	/**
	 * Parse specially formatted games CSV file.
	 * 
	 * TODO: explain CSV file format...
	 * 
	 * @param $infile path to games csv file.
	 * @return array with success/failure message.
	 */
	public function parseGameFile($infile) {
		$csvObj = CSV::fromFile($infile, true);
		$data = $csvObj->toArray();
		
		$prevElem = null;
		$currGame = null;
		$gameTaskPairs = [];
		$tasks = [];
		// Iterate input CSV file
		foreach ($data as $elem) {
			// New game name --> new game
			if($prevElem==null || ($elem['Name']!=$prevElem['Name'] && $elem['Name']!='')) {
				try {
					$currGame = $this->createGame($elem);
				} catch (Exception $e) {
					return [ 'status' => 'Failed',
							 'message' => $e->getMessage()
					 ];
				}
				
				$tasks = [];
				$tmp = [
					'game' => $currGame,
					'tasks' => &$tasks		// Push to array and keep reference to $tasks
				];
				array_push($gameTaskPairs, $tmp);
			}
			
			// New task for current game
			if($currGame!=null) {
				$currTask = $this->createTask($currGame, $elem);
				array_push($tasks, $currTask);
			}
			$prevElem = $elem;
		}
		
		// Now do the saving
		foreach ($gameTaskPairs as $pair) {
			$pair['game']->save();
			$pair['game']->tasks()->saveMany($pair['tasks']);
		}
		
		return [ 'status' => 'Success' ];
	}
	
	/**
	 * Parse specially formatted campaigns CSV file.
	 *
	 * TODO: explain CSV file format...
	 *
	 * @param $infile path to games csv file.
	 * @return array with success/failure message.
	 */
	public function parseCampaignFile($infile) {
		$csvObj = CSV::fromFile($infile, true);
		$data = $csvObj->toArray();
		
		$prevElem = null;
		$currCampaign = null;
		$saveCampaignsPairs = [];
		$currIdx = -1;
		// Iterate input CSV file
		foreach ($data as $elem) {
			// New CampaignType --> new campaign
			if($prevElem==null || ($elem['CampaignType']!=$prevElem['CampaignType'] && $elem['CampaignType']!='')) {
				try {
					$currCampaign = $this->createCampaign($elem);
				} catch (Exception $e) {
					return [ 'status' => 'Failed',
							'message' => $e->getMessage()
					];
				}
				
				$currIdx = $currIdx + 1;
				$saveCampaignsPairs[$currIdx] = [
					'campaign' => $currCampaign,
					'games' => [],		// Push to array and keep reference to $games
					'stories' => []		// ...and $stories
				];
			}
			
			// New game for current campaign
			if($currCampaign!=null) {
				$game = Game::where('name','=', $elem['GameName'])->first();
				
				if($game==null) {
					return [ 'status' => 'Failed',
							'message' => 'Game does not exist: '.$elem['GameName']
					];
				}
				array_push($saveCampaignsPairs[$currIdx]['games'], $game);
				
				if($currCampaign->campaignType->name=='Story') {
					$story = $this->createStory($currCampaign, $elem);
					array_push($saveCampaignsPairs[$currIdx]['stories'], $story);
				}
			}
			$prevElem = $elem;
		}
		
		// Now do the saving
		foreach ($saveCampaignsPairs as $pair) {
			$campaign = $pair['campaign'];
			$campaign->save();
			$campaign->games()->saveMany($pair['games']);
			
			if($campaign->campaignType->name=='Story') {
				foreach($pair['stories'] as $story) {
					$story->campaign_id = $campaign->id;
					$story->save();
					$campaign_stories = new CampaignStories($campaign, $story);
					$campaign_stories->save();
				}
			}
		}
		
		return [ 'status' => 'Success' ];
	}
	
	/**
	 * Construct a new Game instance from the given row on the CSV file.
	 * 
	 * @param $elem A row from the CSV file.
	 * @return a Game instance (yet to be saved).
	 */
	private function createGame($elem) {
		$gameType = GameType::where('name','=', $elem['GameType'])->first();
		if($gameType==null) {
			throw new Exception('GameType not defined');
		}
		
		$game = new Game($gameType);
		$game->level = intval($elem['Level']);
		$game->name = $elem['Name'];
		$game->instructions = $elem['Instructions'];
		$extraInfo = $this->getExtraInfo($elem);
		$game->extraInfo = serialize($extraInfo);
		return $game;
	}
	
	/**
	 * Return an array of columns starting with "Extra info: ". The keys of the array
	 * are named as the column (removing the "Extra info: " prefix.
	 * 
	 * @param $elem Row on a CSV file
	 * @return an array of "Extra info: " columns.
	 */
	private function getExtraInfo($elem) {
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
		return $extraInfo;
	}
	
	/**
	 * Construct a new Campaign instance from the given row on the CSV file.
	 * 
	 * @param $elem A row from the CSV file.
	 * @return a Campaign instance (yet to be saved).
	 */
	private function createCampaign($elem) {
		$campaignType = CampaignType::where('name','=', $elem['CampaignType'])->first();
		if($campaignType==null) {
			throw new Exception('CampaignType not defined');
		}

		$campaign = new Campaign($campaignType);
		$campaign->name = $elem['Name'];
		$campaign->badgeName = $elem['BadgeName'];
		$campaign->description = $elem['Description'];
		$campaign->image = $elem['Image'];
		
		return $campaign;
	}
	
	/**
	 * Construct a new Task instance from the given row on the CSV file.
	 * Task is associated with the given game.
	 * 
	 * @param $elem A row from the CSV file.
	 * @return a Task instance (yet to be saved).
	 */
	private function createTask($game, $elem) {
		$gameTypeName = $game->gameType->name;
		
		$taskType = TaskType::where('name', '=', $gameTypeName)->first();
		$data = $elem['Task data'];
		
		$task = new Task($taskType, $data);
		return $task;
	}
	
	/**
	 * Construct a new Story instance from the given row on the CSV file.
	 * Story is associated with the given campaign.
	 * 
	 * @param $elem A row from the CSV file.
	 * @return a Story instance (yet to be saved).
	 */
	private function createStory($campaign, $elem) {
		$story = new Story($campaign);
		$story->story_string = $elem['Story'];
		$extraInfo = $this->getExtraInfo($elem);
		$story->extraInfo = serialize($extraInfo);
		return $story;
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
	public function installGameType($handlerClass) {
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
