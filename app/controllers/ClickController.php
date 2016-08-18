<?php
/**
 * This controller controlls traffic for updating the database
* with user clicks.
*/
class ClickController extends BaseController {
	public function submitUserAction(){
		if(Auth::user()->check()){
			$page = Input::get('page');
			$id = Input::get('id');
			$userAction = New UserAction();
			$userAction->user_id = Auth::user()->get()->id;
			$userAction->page = $page;
			$userAction->action = $id;
			$userAction->save();
		}
	}
}