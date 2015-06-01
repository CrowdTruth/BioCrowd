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
		return 'img/factor_validation.png';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getView($game) {
		$tasks = $game->tasks;
		$userId = Auth::user()->get()->id;
		// Which image to use ?
		// Select image with minimum number of judgements from current user
		$image = null;
		$taskId = null;
		$minJudgementCounts = -1;
		foreach ($tasks as $task) {
			$nJudgements = Judgement::where('task_id','=',$task->id)
									->where('user_id','=',$userId)->count();
			if($nJudgements<$minJudgementCounts || $image==null) {
				$minJudgementCounts = $nJudgements;
				$image = $task->data;
				$taskId = $task->id;
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
			->with('instructions', $game->instructions)
			->with('image', $image)
			->with('responseLabel', $responseLabel);
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function processResponse($game,$campaignId) {
		//Put the post data into php variables
		$userId = Auth::user()->get()->id;
		$taskId = Input::get('taskId');
		$location = Input::get('location');
		$markingDescription = Input::get('markingDescription');
		$otherExpand = Input::get('otherExpand');
		$qualityDescription = Input::get('qualityDescription');
		$comments = Input::get('comments');
		$comment = Input::get('comment');
		
		$responseArray["location"] = $location;
		$responseArray["markingDescription"] = $markingDescription;
		$responseArray["otherExpand"] = $otherExpand;
		$responseArray["qualityDescription"] = $qualityDescription;
		$responseArray["comments"] = $comments;
		$responseArray["comment"] = $comment;
		
		$response = $this->encodeJudgement($responseArray);
		
		//Create and Submit the judgement model
		$judgement = new Judgement();
		$judgement->user_id = $userId;
		$judgement->task_id = $taskId;
		$judgement->game_id = $game->id;
		$judgement->campaign_id = $campaignId;
		$judgement->response = $response;
		$judgement->basic_score_gained = $game->score;
		$judgement->save();
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function addUserGameScore($score) {
		$user = User::find(Auth::user()->get()->id);
		$oldUserScore = $user->score;
		$user->score = ($score + $oldUserScore);
		$user->save();
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
