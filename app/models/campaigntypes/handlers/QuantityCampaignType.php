<?php
/**
 * GameTypeHandler for CellEx extraction GameType. 
 */
class QuantityCampaignType extends CampaignTypeHandler {

	/**
	 * See GameTypeHandler
	 */
	public function getName() {
		return 'Quantity';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getDescription() {
		return 'Rewarding the user for doing the same game X times';
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getExtrasDiv($extraInfo) {
		$extraInfo = unserialize($extraInfo);
		$label = $extraInfo['label'];
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-2 control-label'>Label:</label>";
		$divHTML .= "<input class='form-control' name='quantityCampaignLabel' type='text' value='".$label."' id='quantityCampaignLabel'>";
		$divHTML .= "";
		return $divHTML;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		$extraInfo['label'] = [];
		$extraInfo['label'] = $inputs['quantityCampaignLabel'];
		return serialize($extraInfo);
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function getThumbnail() {
		return 'img/army_mission.png';
	}
	
	/**
	 * See GameTypeHandler
	 */
	/*public function processResponse($game) {
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
	}*/
	
	/**
	 * See GameTypeHandler
	 */
	/*public function renderTask($task) {
		return $task->data;
	}*/
	
	/**
	 * See GameTypeHandler
	 */
	/*public function validateData($data) {
		return true;
	}*/
}
