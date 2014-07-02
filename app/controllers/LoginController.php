<?php
class LoginController extends BaseController {

	public function requestLogin() {
		return View::make('login');
	}

	public function doLogin() {
		$email = Input::get('email');
		$pass  = Input::get('password');
		if( Auth::attempt( array('email' => $email, 'password' => $pass) )){
			return Redirect::to('/');
		} else {
			return Redirect::to('login')->with('flash_error', 'Invalid email/password combination.')->withInput();
		}
	}

	public function requestLogout() {
		Session::flush();
		Session::regenerate();
		return 'Prompt after logout (BTW your session is destroyed!)';
	}
}
