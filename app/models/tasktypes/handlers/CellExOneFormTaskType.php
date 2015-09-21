<?php
/**
 * TaskTypeHandler for CellEx extraction TaskType. 
 */
class CellExOneFormTaskType extends TaskTypeHandler {

	/**
	 * See TaskTypeHandler
	 */
	public function getName() {
		return 'CellExOneForm';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getDescription() {
		return 'Extracting cells from microscopic images';
	}
	
	/**
	 * See TaskTypeHandler
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
	 * See TaskTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		$extraInfo['label'] = [];
		$extraInfo['label'] = $inputs['cellExLabel'];
		return serialize($extraInfo);
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getThumbnail() {
		return 'img/factor_validation1.png';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function processResponse($task) {
		//Put the post data into php variables
		$userId = Auth::user()->get()->id;
		$taskId = $task->id;
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
		
		return Redirect::to(Lang::get('gamelabels.gameUrl'));
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function renderTask($task) {
		return $task->data;
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function validateData($data) {
		return true;
	}
}
