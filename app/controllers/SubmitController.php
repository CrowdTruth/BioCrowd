<?php
class SubmitController extends BaseController{

	/**
	 * Submit the annotationdata made by the user to the database. 
	 */

	function submitJugement() {
		//Put the post data into php variables
		$user_id = Input::get('user_id');
		$task_id = Input::get('task_id');
		$response = Input::get('response');
		
		//Create and Submit the Jugement model
		$jugement = new Jugement;
		$jugement->user_id = $user_id;
		$jugement->task_id = $task_id;
		$jugement->response = $response;
		//date variable updated automatically, but timezone is incorrect. 
		$jugement->save();
		
		return Redirect::to('game_menu');
	}
}