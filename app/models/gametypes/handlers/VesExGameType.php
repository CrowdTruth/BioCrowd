<?php
/**
 * GameTypeHandler for the VesEx GameType. 
 */
class VesExGameType extends GameTypeHandler {
	
	/**
	 * See GameTypeHandler
	 */
	public function getName() {
		return 'VesEx';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getDescription() {
		return 'Extracting vesicles from microscopic images';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getExtrasDiv($extraInfo) {
		$extraInfo = unserialize($extraInfo);
		
		$label = $extraInfo['label'];
		$label1 = $extraInfo['label1'];
		$label2 = $extraInfo['label2'];
		$label3 = $extraInfo['label3'];
		
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-4 control-label'>Label:</label>";
		$divHTML .= "<input class='form-control' name='cellExLabel' type='text' value='".$label."' id='cellExLabel'>";

		$divHTML .= "<label for='data' class='col-sm-4 control-label'>Label 1:</label>";
		$divHTML .= "<input class='form-control' name='cellExLabel1' type='text' value='".$label1."' id='cellExLabel1'>";
		
		$divHTML .= "<label for='data' class='col-sm-4 control-label'>Label 2:</label>";
		$divHTML .= "<input class='form-control' name='cellExLabel2' type='text' value='".$label2."' id='cellExLabel2'>";
		
		$divHTML .= "<label for='data' class='col-sm-4 control-label'>Label 3:</label>";
		$divHTML .= "<input class='form-control' name='cellExLabel3' type='text' value='".$label3."' id='cellExLabel3'>";
		return $divHTML;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		return '';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getThumbnail() {
		return 'img/icons/image_games-04.png';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getView($game) {
		$tasks = $game->tasks;
		$userId = Auth::user()->get()->id;
		$gameId = $game->id;
		// Which image to use ?
		// Select image with minimum number of judgements from current user
		$image = null;
		$taskId = null;
		$minJudgementCounts = -1;
		
		//Make all variables for giving the response to the cellex view
		$location = '';
		$markingDescription = '';
		$otherExpand = '';
		$qualityDescription = '';
		$comment = '';
		
		//First, try to pick a task out of the judgements with flag attribute "incomplete"
		$judgements = Judgement::where('user_id','=',$userId)->where('flag', '=', 'incomplete')->get();
		foreach($judgements as $judgement){
			foreach ($tasks as $task) {
				if($judgement->task_id == $task->id){
					$nJudgements = Judgement::where('task_id','=',$task->id)
											->where('user_id','=',$userId)
											->where('flag', '=', 'incomplete')
											->count();
					if($nJudgements<$minJudgementCounts || $image==null) {
						$minJudgementCounts = $nJudgements;
						$image = $task->data;
						$taskId = $task->id;
					}
				}
			}
		}
		//if the image is not null, the task that will be given to the cellEx is incomplete
		//and we need to pass the existing information in the DB to the cellEx view. 
		if($image != null){
			$judgement = Judgement::where('user_id', $userId)->where('task_id', $taskId)->where('flag', 'incomplete')->orderBy('id', 'DESC')->first();
			$response = unserialize($judgement->response);
			$location = $response['location'];
			$markingDescription = $response['markingDescription'];
			$otherExpand = $response['otherExpand'];
			$qualityDescription = $response['qualityDescription'];
			$comment = $response['comment'];
		} else {
		//if the image is null, try to pick a task out of the tasks regardless of the flag attribute. 
			foreach ($tasks as $task) {
				$nJudgements = Judgement::where('task_id','=',$task->id)
										->where('user_id','=',$userId)->count();
				if($nJudgements<$minJudgementCounts || $image==null) {
					$minJudgementCounts = $nJudgements;
					$image = $task->data;
					$taskId = $task->id;
				}
			}
		}
		
		$extraInfo = unserialize($game['extraInfo']);
		$responseLabel[0] = $extraInfo['label'];
		$responseLabel[1] = $extraInfo['label1'];
		$responseLabel[2] = $extraInfo['label2'];
		$responseLabel[3] = $extraInfo['label3'];
		$responseLabel[4] = $extraInfo['label4'];
		$responseLabel[5] = $extraInfo['label5'];
		
		return View::make('vesex')
			->with('gameId', $game->id)
			->with('taskId', $taskId)
			->with('gameName', $game->name)
			->with('instructions', $game->instructions)
			->with('examples', $game->examples)
			->with('steps', $game->steps)
			->with('image', $image)
			->with('responseLabel', $responseLabel)
			->with('location', $location)
			->with('markingDescription', $markingDescription)
			->with('otherExpand', $otherExpand)
			->with('qualityDescription', $qualityDescription)
			->with('comment', $comment);
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function processResponse($game,$campaignId) {	
		//Put the post data into php variables
		$userId = Auth::user()->get()->id;
		$taskId = Input::get('taskId');
		$gameId = $game->id;
		$flag = Input::get('flag');
		$otherExpandWasChanged = Input::get('otherExpandWasChanged');
		$commentWasChanged = Input::get('commentWasChanged');
		$location = Input::get('location');
		$markingDescription = Input::get('markingDescription');
		$otherExpand = Input::get('otherExpand');
		$qualityDescription = Input::get('qualityDescription');
		$comment = Input::get('comment');
		
		$responseArray["location"] = $location;
		$responseArray["markingDescription"] = $markingDescription;
		$responseArray["otherExpand"] = $otherExpand;
		$responseArray["qualityDescription"] = $qualityDescription;
		$responseArray["comment"] = $comment;
		
		$response = $this->encodeJudgement($responseArray);
		
		if($flag != 'incomplete') {
			//check if there was already a task with the same userId, taskId, gameId and campaignId that was incomplete
			$existingJudgement = Judgement::where('user_id', $userId)->where('task_id', $taskId)->where('game_id', $gameId)->where('campaign_id', $campaignId)->where('flag', 'incomplete')->orderBy('id', 'DESC')->first();
			//if so, fill the variables with the responses that were in the database already and update the response field
			if($existingJudgement){
				$oldResponseArray = $this->decodeJudgement($existingJudgement->response);
				$newResponseArray = $this->makeNewResponseArray($responseArray, $oldResponseArray);
				if(($otherExpandWasChanged != 'false') && ($otherExpand == "")){
					$newResponseArray["otherExpand"] = $otherExpand;
				}
				if(($commentWasChanged != 'false') && ($comment == "")){
					$newResponseArray["comment"] = $comment;
				}
				$newResponse = $this->encodeJudgement($newResponseArray);
				$existingJudgement->response = $newResponse;
				$existingJudgement->flag = $flag;
				$existingJudgement->save();
			} else {
				//if not, make a new judgement and overwrite the flag. This should not happen since you cannot finish
				//without answering questions and therefore creating an incomplete judgement.
				$judgement = new Judgement();
				$judgement->user_id = $userId;
				$judgement->task_id = $taskId;
				$judgement->game_id = $gameId;
				$judgement->campaign_id = $campaignId;
				$judgement->response = $response;
				$judgement->flag = $flag;
				$judgement->save();
			}
		} else {
			//check if there was already a task with the same userId, taskId, gameId and campaignId that was incomplete
			$existingJudgement = Judgement::where('user_id', $userId)->where('task_id', $taskId)->where('game_id', $gameId)->where('campaign_id', $campaignId)->where('flag', 'incomplete')->orderBy('id', 'DESC')->first();
			//if so, fill the variables with the responses that were in the database already and update the response field
			if($existingJudgement){
				$oldResponseArray = $this->decodeJudgement($existingJudgement->response);
				$newResponseArray = $this->makeNewResponseArray($responseArray, $oldResponseArray);
				if(($otherExpandWasChanged != 'false') && ($otherExpand == "")){
					$newResponseArray["otherExpand"] = $otherExpand;
				}
				if(($commentWasChanged != 'false') && ($comment == "")){
					$newResponseArray["comment"] = $comment;
				}
				$newResponse = $this->encodeJudgement($newResponseArray);
				$existingJudgement->response = $newResponse;
				$existingJudgement->save();
			} else {
				//If not, make a new judgement with flag incomplete.
				$judgement = new Judgement();
				$judgement->user_id = $userId;
				$judgement->task_id = $taskId;
				$judgement->game_id = $gameId;
				$judgement->campaign_id = $campaignId;
				$judgement->response = $response;
				$judgement->flag = 'incomplete';
				$judgement->save();
			}
		}
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function makeNewResponseArray($response, $oldResponse){
		//TODO: add a flag to decide whether to override the coordinates attribute because the user removed all coordinates.
		$newResponse = $oldResponse;
		foreach($response as $responseAttribute => $value){
			if($value != null){
				$newResponse[$responseAttribute] = $value;
			}
		}
		return $newResponse;
	}
	
	
	/**
	 * See GameTypeHandler
	 */
	public function encodeJudgement($judgement) {
		return serialize($judgement);
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function decodeJudgement($judgementStr) {
		return unserialize($judgementStr);
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function renderGame($game) {
		return $game->data;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function validateData($data) {
		return true;
	}
}
