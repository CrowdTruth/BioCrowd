<?php
/**
 * This Controller handles traffic for the admin interface.
 */
class AdminController extends BaseController {
	public function __construct() {
		$this->beforeFilter('adminauth', [ 'only' => 'getIndex' ]);
	}
	
	/**
	 * Display admin home screen.
	 */
	public function getIndex() {
		return View::make('admin.home');
	}
	
	/**
	 * Redirect to the Login Blade page.
	 */
	public function getLogin() {
		return View::make('admin.login');
	}
	
	/**
	 * Perform login action.
	 */
	public function postLogin() {
		$user = Input::get('username');
		$pass  = Input::get('password');
		if( Auth::admin()->attempt( [ 'username' => $user, 'password' => $pass ] )){
			return Redirect::to('admin');
		} else {
			return Redirect::to('admin/login')
				->with('flash_error', 'Invalid email/password combination.')
				->withInput();
		}
	}
	
	/**
	 * Perform logout action.
	 */
	public function getLogout() {
		Session::flush();
		Session::regenerate();
		return Redirect::to('admin/login')->with('flash_error', 'You have been logged out.');
	}
}
