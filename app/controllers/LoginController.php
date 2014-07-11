<?php
class LoginController extends BaseController {
	private static $INVITE_CODE = 'CrowdWatson';

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

	public function doRegister() {
		$email = Input::get('email');
		$name  = Input::get('name');
		$pass  = Input::get('password');
		$pass2 = Input::get('password2');
		$code  = Input::get('code');

		if($pass!=$pass2) {
			return Redirect::to('login')->with('flash_error', 'Passwords do not match.')->withInput();
		}
		if($code!=self::$INVITE_CODE) {
			return Redirect::to('login')->with('flash_error', 'Invalid invitation code.')->withInput();
		}
		try {
			// Validate user exists
			// Validate password not-null
			$user = new User;
			$user->email = $email;
			$user->name = $name;
			$user->password = Hash::make($pass);
			$user->save();
			return 'Register user: ' . $email . ' , ' . $pass  . ' , ' . $pass2 . ' , ' . $code . '<br>Login & redirect to game';
		} catch (Exception $e) {
			return 'Could not create user: ' . $email . '  => ' . $e;
		}
	}

	public function requestLogout() {
		Session::flush();
		Session::regenerate();
		return Redirect::to('login')->with('flash_error', 'You have been logged out.');
	}
}
