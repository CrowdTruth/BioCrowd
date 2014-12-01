<?php
class SubmitController extends BaseController{

	/**
	 * Submit the annotationdata made by the user to the database. 
	 */

	function submitJudgement() {
		//Put the post data into php variables
		$user_id = Input::get('user_id');
		$game_id = Input::get('game_id');
		$response = Input::get('response');
		
		//Create and Submit the judgement model
		$judgement = new judgement;
		$judgement->user_id = $user_id;
		$judgement->game_id = $game_id;
		$judgement->response = $response;
		//date variable updated automatically, but timezone is incorrect. 
		$judgement->save();
		
		return Redirect::to('gameMenu');
	}
}
