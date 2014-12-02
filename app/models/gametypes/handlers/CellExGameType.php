<?php
class CellExGameType extends GameTypeHandler {

	public function getName() {
		return 'CellEx';
	}
	
	public function getDescription() {
		return 'Extracting cells from microscopic images';
	}
	
	public function getDataDiv() {
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-2 control-label'>Image URL:</label>";
		$divHTML .= "	<div class='col-xs-3'>";
		$divHTML .= "		<input class='form-control' name='cellExImage' type='text' value='' id='cellExImage'>";
		$divHTML .= "	</div>";
		return $divHTML;
	}

	public function parseInputs($inputs) {
		return $inputs['cellExImage'];
	}
	
	public function getThumbnail() {
		return 'img/factor_validation1.png';
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
		
		return View::make('cellex')
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
		$response = Input::get('response');
		
		//Create and Submit the judgement model
		$judgement = new Judgement();
		$judgement->user_id = $userId;
		$judgement->task_id = $taskId;
		$judgement->response = $response;
		$judgement->save();
		
		return Redirect::to('gameMenu');
	}
}
