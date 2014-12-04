<?php
class VesExGameType extends GameTypeHandler {

	public function getName() {
		return 'VesEx';
	}
	
	public function getDescription() {
		return 'Extracting vesicles from microscopic images';
	}
	
	public function getDataDiv() {
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-2 control-label'>Image URL:</label>";
		$divHTML .= "	<div class='col-xs-3'>";
		$divHTML .= "		<input class='form-control' name='vesExImage' type='text' value='' id='vesExImage'>";
		$divHTML .= "	</div>";
		return $divHTML;
	}

	public function parseInputs($inputs) {
		return $inputs['vesExImage'];
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
		$response = json_encode (["Distributed" => $distributed, "Tip" => $tip, "Nucleus" => $nucleus]);
		
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
}
