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
		$divHTML = "No additional information provided for each game.";
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
		$responseLabel = $extraInfo['label'];
		$label1 = $extraInfo['label1'];
		$label2 = $extraInfo['label2'];
		$label3 = $extraInfo['label3'];
		
		return View::make('vesex')
			->with('gameId', $game->id)
			->with('taskId', $taskId)
			->with('instructions', $game->instructions)
			->with('image', $image)
			->with('responseLabel', $responseLabel)
			->with('label1', $label1)
			->with('label2', $label2)
			->with('label3', $label3);
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function processResponse($game) {
		//Put the post data into php variables
		$userId = Auth::user()->get()->id;
		$taskId = Input::get('taskId');
		$distributed = Input::get('distributed');
		$tip = Input::get('tip');
		$nucleus = Input::get('nucleus');
		$novesicles = Input::get('novesicles');
		//setValueToNo($distributed); //somehow it doesn't see the function even though it's down there! Composer dumpautoload doesn't fix this. 
		//setValueToNo($tip);
		//setValueToNo($nucleus);
		if($distributed == null){
			$distributed = "No";
		}
		if($tip == null){
			$tip = "No";
		}
		if($nucleus == null){
			$nucleus = "No";
		}
		if($novesicles == null){
			$novesicles = "false";
		}
		$response = serialize(["Distributed" => $distributed, "Tip" => $tip, "Nucleus" => $nucleus, "No Vesicles => $novesicles"]);
		
		//Create and Submit the judgement model
		$judgement = new Judgement();
		$judgement->user_id = $userId;
		$judgement->task_id = $taskId;
		$judgement->response = $response;
		$judgement->save();
		
		return Redirect::to('gameMenu');
	}
	
	// TODO: document (or remove if not used)
	function setValueToNo($value){
		if($value == null){
			$value = "No";
		}
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
