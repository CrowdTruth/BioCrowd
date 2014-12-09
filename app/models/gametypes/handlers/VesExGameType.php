<?php
class VesExGameType extends GameTypeHandler {

	public function getName() {
		return 'VesEx';
	}
	
	public function getDescription() {
		return 'Extracting vesicles from microscopic images';
	}
	
	public function getExtrasDiv($extraInfo) {
		$divHTML = "No additional information provided for each game.";
		return $divHTML;
	}

	public function parseExtraInfo($inputs) {
		return '';
	}
	
	public function getThumbnail() {
		return 'img/factor_validation.png';
	}
	
	public function getView($game) {
		$tasks = $game->tasks();
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
		
		return View::make('vesex')
			->with('gameId', $game->id)
			->with('taskId', $taskId)
			->with('instructions', $game->instructions)
			->with('image', $image)
			;
	}
	
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
	
	function setValueToNo($value){
		if($value == null){
			$value = "No";
		}
	}
	
	public function renderTask($task) {
		return $task->data;
	}
	
	public function validateData($data) {
		return true;
	}
}
