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
	
	public function editProfile() {
		$email = Auth::user()->get()->email;
		$pass  = Input::get('password');
		$newemail = Input::get('email');
		$name  = Input::get('name');
		$cellBioExpertise  = Input::get('cellBioExpertise');
		$expertise = Input::get('expertise');
		//Check if the email already exists by trying to get it from the database. If that fails, the e-mail adress is not in use yet.
		if(User::where('email', $newemail)->first()){
			//The email is in use. 
			//Check if the user filled in their own e-mail. If so, continue without updating the e-mail field of the database. 
			if(Auth::user()->get()->email == $newemail){
				if( Auth::user()->attempt( [ 'email' => $email, 'password' => $pass ] )){
					$user = User::find(Auth::user()->get()->id);
					$user->name = $name;
					$user->cellBioExpertise = $cellBioExpertise;
					$user->expertise = $expertise;
					$user->save();
					return Redirect::to('profile')->with('flash_error', 'Your account information has been changed')->withInput();
				} else {
					return Redirect::to('profile')->with('flash_error', 'Invalid password given. If you want to change your password, do so in the "Password" section. Guest users have no current password and can leave this field empty. ')->withInput();
				}
			} else {
				//If the user filled in an existing e-mail, but not their own e-mail adress, return with a message to inform the user about this
				return Redirect::to('profile')->with('flash_error', 'E-mail adress '.$newemail.' is already in use. Try another one. ')->withInput();
			}
		} else {
			//The email is new. Change all info including the e-mail adress
			if( Auth::user()->attempt( [ 'email' => $email, 'password' => $pass ] )){
				$user = User::find(Auth::user()->get()->id);
				$user->email = $newemail;
				$user->name = $name;
				$user->cellBioExpertise = $cellBioExpertise;
				$user->expertise = $expertise;
				$user->save();
				return Redirect::to('profile')->with('flash_error', 'Your account information has been changed')->withInput();
			} else {
				return Redirect::to('profile')->with('flash_error', 'Invalid password given. If you want to change your password, do so in the "Password" section. Guest users have no current password and can leave this field empty. ')->withInput();
			}
		}
	}
}