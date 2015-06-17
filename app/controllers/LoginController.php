<?php
/**
 * This controller controls login / logout and registration from users to the game.
 */
class LoginController extends BaseController {
	// TODO: persist $INVITE_CODE on database ?
	// Maybe use something like this: https://github.com/patkruk/Laravel-Cached-Settings
	// Or a Settings model of our own?
	private static $INVITE_CODE = 'm0ng0';

	/**
	 * Display Blade view of login page.
	 */
	public function requestLogin() {
		if(Auth::user()->check()) {
			return Redirect::to('/');
		}
		return View::make('login');
	}
	
	/**
	 * Perform login with given email/password.
	 */
	public function doLogin() {
		$email = Input::get('email');
		$pass  = Input::get('password');
		if( Auth::user()->attempt( [ 'email' => $email, 'password' => $pass ] )){
			return Redirect::to('/');
		} else {
			return Redirect::to('login')
				->with('flash_error', 'Invalid email/password combination.')
				->withInput();
		}
	}
	
	/**
	 * Perform user registration.
	 */
	public function doRegister() {
		$email = Input::get('email');
		$name  = Input::get('name');
		$pass  = Input::get('password');
		$pass2 = Input::get('password2');
		$code  = Input::get('code');
		$bioExpert = Input::get('bioExpert');
		$expertise = Input::get('expertise');

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
			$user->bioExpert = $bioExpert;
			$user->expertise = $expertise;
			$user->save();
			
			return $this->doLogin();
		} catch (Exception $e) {
			return Redirect::to('login')->with('flash_error', 'Could not create user: ' . $email . '  => ' . $e)->withInput();
		}
	}
	
	/**
	 * Perform logout.
	 */
	public function requestLogout() {
		Session::flush();
		Session::regenerate();
		return Redirect::to('login')->with('flash_error', 'You have been logged out.');
	}
}
