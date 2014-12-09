<?php
class CellExGameType extends GameTypeHandler {

	public function getName() {
		return 'CellEx';
	}
	
	public function getDescription() {
		return 'Extracting cells from microscopic images';
	}
	
	public function getExtrasDiv($extraInfo) {
		$extraInfo = unserialize($extraInfo);
		$label = $extraInfo['label'];
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-2 control-label'>Label:</label>";
		$divHTML .= "<input class='form-control' name='cellExLabel' type='text' value='".$label."' id='cellExLabel'>";
		$divHTML .= "";
		return $divHTML;
	}

	public function parseExtraInfo($inputs) {
		$extraInfo['label'] = [];
		$extraInfo['label'] = $inputs['cellExLabel'];
		return serialize($extraInfo);
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
		$extraInfo = unserialize($game['extraInfo']);
		$resposeLabel = $extraInfo['label'];
		
		return View::make('cellex')
			->with('gameId', $game->id)
			->with('taskId', $taskId)
			->with('instructions', $game->instructions)
			->with('image', $image)
			->with('resposeLabel', $resposeLabel)
			;
	}
	
	public function processResponse($game) {
		//Put the post data into php variables
		$userId = Auth::user()->get()->id;
		$taskId = Input::get('taskId');
		$noCells = Input::get('noCells');
		
		if($noCells == null){
			$noCells = "false";
		}
		
		$responseArray["NoCellsOrVesicles"] = $noCells;
		$tempCoords = json_decode(Input::get('response'));
		$responseArray["Coordinates"] = $tempCoords;
		$response = serialize($responseArray);
		//$response = Input::get('response');
		
		//Create and Submit the judgement model
		$judgement = new Judgement();
		$judgement->user_id = $userId;
		$judgement->task_id = $taskId;
		$judgement->response = $response;
		$judgement->save();
		
		return Redirect::to('gameMenu');
	}
	
	public function renderTask($task) {
		return $task->data;
	}
	
	public function validateData($data) {
		return true;
	}
}
