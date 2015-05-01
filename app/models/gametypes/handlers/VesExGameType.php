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
		return serialize([ 
				'label'  => $inputs['cellExLabel'],
				'label1' => $inputs['cellExLabel1'],
				'label2' => $inputs['cellExLabel2'],
				'label3' => $inputs['cellExLabel3']
			]);
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
		$distributed = Input::get('distributed', 'No');
		$tip = Input::get('tip', 'No');
		$nucleus = Input::get('nucleus', 'No');
		$novesicles = Input::get('novesicles');
		
		$response = $this->encodeJudgement([
				"Distributed" => $distributed,
				"Tip" => $tip,
				"Nucleus" => $nucleus,
				"No Vesicles" => $novesicles
			]);
		
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
