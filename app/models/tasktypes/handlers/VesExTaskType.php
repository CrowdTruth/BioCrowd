<?php
/**
 * TaskTypeHandler for the VesEx TaskType. 
 */
class VesExTaskType extends TaskTypeHandler {
	
	/**
	 * See TaskTypeHandler
	 */
	public function getName() {
		return 'VesEx';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getDescription() {
		return 'Extracting vesicles from microscopic images';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getExtrasDiv($extraInfo) {
		$divHTML = "No additional information provided for each task.";
		return $divHTML;
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		return '';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getThumbnail() {
		return 'img/factor_validation.png';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function processResponse($task) {
		//Put the post data into php variables
		$userId = Auth::user()->get()->id;
		$taskId = $task->id;
		$distributed = Input::get('distributed');
		$tip = Input::get('tip');
		$nucleus = Input::get('nucleus');
		$novesicles = Input::get('novesicles');
		//setValueToNo($distributed); //somehow it doesn't see the function even though it's down there! Composer dumpautoload doesn't fix this. TODO: fix this!
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
		
		return Redirect::to(Lang::get('gamelabels.gameUrl'));
	}
	
	// TODO: document (or remove if not used)
	function setValueToNo($value){
		if($value == null){
			$value = "No";
		}
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
