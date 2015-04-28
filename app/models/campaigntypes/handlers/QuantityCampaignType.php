<?php
/**
 * CampaignTypeHandler for QuantityCampaignType. 
 */
class QuantityCampaignType extends CampaignTypeHandler {

	/**
	 * See CampaignTypeHandler
	 */
	public function getName() {
		return 'Quantity';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getDescription() {
		return 'Rewarding the user for doing the same game X times';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getExtrasDiv($extraInfo) {
		$extraInfo = unserialize($extraInfo);
		$label = $extraInfo['label'];
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-2 control-label'>Label:</label>";
		$divHTML .= "<input class='form-control' name='quantityCampaignLabel' type='text' value='".$label."' id='quantityCampaignLabel'>";
		$divHTML .= "";
		return $divHTML;
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		$extraInfo['label'] = [];
		$extraInfo['label'] = $inputs['quantityCampaignLabel'];
		return serialize($extraInfo);
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getThumbnail() {
		return 'img/army_mission.png';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getView($campaign) {
		// Get parameter campaignId
		$campaignId = $campaign->id;
			
		//Retrieve the array of games that this campaign entails
		$crude_game_array = CampaignGames::where('campaign_id',$campaignId)->select('game_id')->get()->toArray();
		$game_array = array_column($crude_game_array, 'game_id');
		
		//Find out if this campaign is in the campaign_progress table and if that entry has this user_id. If not: Just give the first gameId in the game_array.
		$testvariable = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaignId)->first(['number_performed']);
		
		if(count($testvariable) < 1){
			$numberPerformed = 0;
		} else {
			//Find out what the next game is for this user in this campaign
			$numberPerformed = $testvariable['number_performed'];
		}
		
		$gameId = $game_array[$numberPerformed];
		
		//Put the next consecutive game in the game variable
		$game = Game::find($gameId);
		
		// Use corresponding game controller to display game.
		$handlerClass = $game->gameType->handler_class;
		$handler = new $handlerClass();
		
		//build the view with all extra info that is in the "extraInfo" column of the game model
		$view = $handler->getView($game);
		foreach(unserialize($game['extraInfo']) as $key=>$value){
			$view = $view->with($key, $value);
		}
		$view = $view->with('campaignMode', true);
		//$view = $view->with('gameOrigin', false);
		if(isset($responseLabel) && $responseLabel != null){
			$view = $view->with('responseLabel', $responseLabel); //to overwrite any responselabel of the non-campaignMode game
		}
		$view = $view->with('campaignIdArray', $campaignId);
		return $view;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function processResponse($campaign,$gameOrigin,$gameId) {
		$this->updateCampaignProgress($campaign,$gameId);
		
		//if the user came here from a game instead of a campaign, redirect to the game menu
		if($gameOrigin){
			return Redirect::to('gameMenu');
		} else {
			$nextGame = $this->selectNextGameInCampaign($campaign);
			//return to next cammpaign or campaign overview page if the campaign is done.
			if($nextGame){
				return Redirect::to('playCampaign?campaignIdArray='.$campaign->id);
			} else {
				return Redirect::to('campaignMenu');
			}
		}
	}
	
	function selectNextGameInCampaign($campaign){
		//Retrieve the array of games that this campaign entails
		$crude_game_array = CampaignGames::where('campaign_id',$campaignId)->select('game_id')->get()->toArray();
		$game_array = array_column($crude_game_array, 'game_id');
		//subtract the games that have already been done (with campaign_has_game_id)
		//choose the first game in the array and return it
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function renderCampaign($game) {
		return "";
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function validateData($data) {
		return "";
	}
	
	function updateCampaignProgress($campaign,$gameId){
		$userId = Auth::user()->get()->id;
		//get the amount of tasks performed by this user
		/*$testvariable = CampaignProgress::where('user_id',Auth::user()->get()->id)->where('campaign_id',$campaign->id)->first(['number_performed']);
		//$campaignProgress1 = Jugement::where('user_id',Auth::user()->get()->id)->where('');
		$numberPerformed;
		if(count($testvariable) < 1){
			$numberPerformed = 0;
		} else {
			//Find out what the next game is for this user in this campaign
			$numberPerformed = $testvariable['number_performed'];
		}*/
		
		//select task_id from judgements
		//where user_id = currentUserId
		//raw count the result and put this in an array
		$tasksCountDoneByUser = DB::table('judgements')
		->where('user_id',$userId)
		->select('tasks.id as taskId',DB::raw('count(*) as nTasks'))
		->get();
		//
		//select task_id from tasks
		//right join this with tasksCountDoneByUser
		//order by nTasks
		$totalTasksCountForUser = DB::table('tasks')
		->join($tasksCountDoneByUser, 'tasks.id', '=', $tasksCountDoneByUser->id)
		->select($tasksCountDoneByUser->id,DB::raw('count(*) as nTasks')) //select tasksCountDoneByUser, select all tasks, and join them with a for loop for tasks(if task in tasksCountDoneByUser find taskId and use the count, if not, use 0
		->orderBy('nTasks')
		->get();
		
		dd($totalTasksCountForUser);
		
		$campaignProgressForThisUser = CampaignProgress::where('user_id',$userId)->where('campaign_id',$campaign->id)->select('campaign_has_game_id')->get(); //this is empty if this user has no progress for this campaign yet
		if($campaignProgressForThisUser){
			$crudeCampaignGamesIdArray = CampaignGames::where('campaign_id',$campaign->id)->where('game_id',$gameId)->whereNotIn('id',$campaignProgressForThisUser)->select('id')->get()->toArray();
			$campaignGamesIdArray = array_column($crudeCampaignGamesIdArray, 'id');
		} else {
			$crudeCampaignGamesIdArray = CampaignGames::where('campaign_id',$campaign->id)->where('game_id',$gameId)->select('id')->get()->toArray();
			$campaignGamesIdArray = array_column($crudeCampaignGamesIdArray, 'id');
		}
		
		dd($campaignGamesIdArray);
		
		//see if there are campaignGameId's that are already in the campaignProgress table and select the ones that are NOT in there
		//$campaignProgressGamesIdArray = CampaignProgress::
		
		$campaignGamesId;
		if(count($testvariable) < 1){
			$campaignGamesId = false;
		} else {
			$campaignGamesId = $testvariable['id'];
		}
			
		if($numberPerformed == 0){
			//Since there is no entry in the campaign_progress table yet, make a new campaignProgress model.
			$campaignProgress = new CampaignProgress;
			//fill it with all important information
			$campaignProgress->campaign_has_game_id = $campaignGamesId;
			$campaignProgress->number_performed = $numberPerformed+1;
			$campaignProgress->campaign_id = $campaign->id;
			$campaignProgress->user_id = $userId;
			//and save it to the database
			$campaignProgress->save();
		} else {
			//get the campaignProgress entry you need to edit
			$campaignProgress = CampaignProgress::where('user_id',$userId)->where('campaign_id',$campaign->id)->first();
			//edit the number_performed in the campaignProgress model and save to the database
			$campaignProgress->number_performed = $numberPerformed+1;
			$campaignProgress->save();
		}
	}
}