<?php
/**
 * GameTypeHandler for CellEx extraction GameType.
 */
class CellExGameType extends GameTypeHandler {

	/**
	 * See GameTypeHandler
	 */
	public function getName() {
		return 'CellEx';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getDescription() {
		return 'Extracting cells from microscopic images';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getExtrasDiv($extraInfo) {
		$extraInfo = unserialize($extraInfo);
		$label = $extraInfo['label'];
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-2 control-label'>Label:</label>";
		$divHTML .= "<input class='form-control' name='cellExLabel' type='text' value='".$label."' id='cellExLabel'>";
		$divHTML .= "";
		return $divHTML;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		$extraInfo['label'] = [];
		$extraInfo['label'] = $inputs['cellExLabel'];
		return serialize($extraInfo);
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getThumbnail() {
		return 'img/factor_validation1.png';
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
		$resposeLabel = $extraInfo['label'];
		
		return View::make('cellex')
			->with('gameId', $game->id)
			->with('taskId', $taskId)
			->with('instructions', $game->instructions)
			->with('image', $image)
			->with('resposeLabel', $resposeLabel);
	}
	
	/**
	 * See GameTypeHandler
	 */
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
		
		//Create and Submit the judgement model
		$judgement = new Judgement();
		$judgement->user_id = $userId;
		$judgement->task_id = $taskId;
		$judgement->response = $response;
		$judgement->save();
		
		return Redirect::to('gameMenu');
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function renderTask($task) {
		return $task->data;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function validateData($data) {
		return true;
	}
}
