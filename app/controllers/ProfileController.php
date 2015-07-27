<?php
/**
 * This controller controls edits for user profiles.
 */
class ProfileController extends BaseController {
	
	/**
	 * Returns the view "profile"
	 */
	public function getView() {
		return View::make('profile');
	}
	
	/**
	 * Change the password of the user with the given e-mail adress
	 */
	public function changePassword() {
		$email = Input::get('email');
		$pass  = Input::get('password');
		$newpass  = Input::get('newpassword');
		$newpass2 = Input::get('newpassword2');
		
		//Check if both passwords are the same. If not, tell the user this. 
		if($newpass!=$newpass2) {
			return Redirect::to('profile')->with('flash_error', 'Passwords do not match.')->withInput();
		}
		
		//Check if the password is correct. If so, change the password to the new password that was given and tell the user. 
		if( Auth::user()->attempt( [ 'email' => $email, 'password' => $pass ] )){
			//replace the password of the current user id with the given password
			$user = User::find(Auth::user()->get()->id);
			$user->password = Hash::make($newpass);
			$user->save();
			return Redirect::to('profile')->with('flash_error', 'Your password has been changed')->withInput();
		} else {
			return Redirect::to('profile')->with('flash_error', 'Invalid email/password combination.')->withInput();
		}
	}
}