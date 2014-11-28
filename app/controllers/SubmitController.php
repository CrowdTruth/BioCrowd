<?php
class SubmitController extends BaseController{

	/**
	 * Submit the annotationdata made by the user to the database. 
	 */

	function submitJudgement() {
		//Put the post data into php variables
		$user_id = Input::get('user_id');
		$task_id = Input::get('task_id');
		$response = Input::get('response');
		
		//Create and Submit the judgement model
		$judgement = new judgement;
		$judgement->user_id = $user_id;
		$judgement->task_id = $task_id;
		$judgement->response = $response;
		//date variable updated automatically, but timezone is incorrect. 
		$judgement->save();
		
		return Redirect::to('gameMenu');
	}
}
