<?php
/**
 * This controller controls edits for user profiles.
 */
class ProfileController extends BaseController {
	
	/**
	 * Returns the view "profile"
	 */
	public function getView() {
		$userId = Auth::user()->get()->id;
		//get the last 5 badges of this user
		$userHasBadges = UserHasBadge::where('user_id',$userId)
		->select('name','image','text','user_has_badge.created_at')
		->orderBy('user_has_badge.created_at','desc')
		->take(5)
		->join('badges','user_has_badge.badge_id','=','badges.id')
		->get()
		->toArray();
		
		//get the last 5 scores of this user
		$userScores = Score::where('user_id',$userId)
		->select('campaign_id','score_gained','description','created_at')
		->orderBy('created_at','desc')
		->take(5)
		->get()
		->toArray();
		
		//get the last 5 campaign scores of this user
		$userCampaignScores = Score::where('user_id',$userId)
		->select('campaign_id','score_gained','description','created_at')
		->orderBy('created_at','desc')
		->where('campaign_id', '!=', 'null')
		->take(5)
		->get()
		->toArray();
		
		//get the userPreference model for this user, if it exists
		if(UserPreference::where('user_id',$userId)->first()){
			//save all preferences in variables
			$userPreference = UserPreference::where('user_id',$userId)->first();
			$userPreferenceCampaignsNotification = $userPreference->campaignsNotification;
			$userPreferenceNewsNotification = $userPreference->newsNotification;
			$userPreferencePlayReminder = $userPreference->playReminder;
			$userPreferenceBadegesSection = $userPreference->badgesSection;
			$userPreferenceScoresSection = $userPreference->scoresSection;
			$userPreferenceAccountSection = $userPreference->accountSection;
			$userPreferencePasswordSection = $userPreference->passwordSection;
			$userPreferenceNotificationsSection = $userPreference->notificationsSection;
		} else {
			$userPreferenceCampaignsNotification = 'immediately';
			$userPreferenceNewsNotification = 'immediately';
			$userPreferencePlayReminder = 'daily';
			$userPreferenceBadegesSection = 'collapsed';
			$userPreferenceScoresSection = 'collapsed';
			$userPreferenceAccountSection = 'collapsed';
			$userPreferencePasswordSection = 'collapsed';
			$userPreferenceNotificationsSection = 'collapsed';
		}
		
		return View::make('profile')
		->with('userHasBadges',$userHasBadges)
		->with('userScores',$userScores)
		->with('userCampaignScores',$userCampaignScores)
		->with('userPreferenceCampaignsNotification',$userPreferenceCampaignsNotification)
		->with('userPreferenceNewsNotification',$userPreferenceNewsNotification)
		->with('userPreferencePlayReminder',$userPreferencePlayReminder)
		->with('userPreferenceBadegesSection',$userPreferenceBadegesSection)
		->with('userPreferenceScoresSection',$userPreferenceScoresSection)
		->with('userPreferenceAccountSection',$userPreferenceAccountSection)
		->with('userPreferencePasswordSection',$userPreferencePasswordSection)
		->with('userPreferenceNotificationsSection',$userPreferenceNotificationsSection);
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
					$user->password = Hash::make($pass);
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
				$user->password = Hash::make($pass);
				$user->cellBioExpertise = $cellBioExpertise;
				$user->expertise = $expertise;
				$user->save();
				return Redirect::to('profile')->with('flash_error', 'Your account information has been changed')->withInput();
			} else {
				return Redirect::to('profile')->with('flash_error', 'Invalid password given. If you want to change your password, do so in the "Password" section. Guest users have no current password and can leave this field empty. ')->withInput();
			}
		}
	}
	
	public function convertGuestToUser() {
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
				if( Auth::user()->attempt( [ 'email' => $email, 'password' => '' ] )){
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
			if( Auth::user()->attempt( [ 'email' => $email, 'password' => '' ] )){
				$user = User::find(Auth::user()->get()->id);
				$user->email = $newemail;
				$user->name = $name;
				$user->cellBioExpertise = $cellBioExpertise;
				$user->expertise = $expertise;
				$user->guest_user = false;
				$user->save();
				return Redirect::to('profile')->with('flash_error', 'Your account information has been changed')->withInput();
			} else {
				return Redirect::to('profile')->with('flash_error', 'Invalid password given. If you want to change your password, do so in the "Password" section. Guest users have no current password and can leave this field empty. ')->withInput();
			}
		}
	}
	
	public function editNotificationPreferences() {
		$campaignsNotification = Input::get('campaignsNotification');
		$newsNotification = Input::get('newsNotification');
		$playReminder = Input::get('playReminder');
		$userId = Auth::user()->get()->id;
		
		//Check if this user already has preferences set
		if(UserPreference::where('user_id',$userId)->first()){
			//edit the old model
			$userPreference = UserPreference::where('user_id',$userId)->first();
			$userPreference->campaignsNotification = $campaignsNotification;
			$userPreference->newsNotification = $newsNotification;
			$userPreference->playReminder = $playReminder;
			$userPreference->save();
			return Redirect::to('profile');
		} else {
			//make a new model
			$userPreference = new UserPreference();
			$userPreference->user_id = $userId;
			$userPreference->campaignsNotification = $campaignsNotification;
			$userPreference->newsNotification = $newsNotification;
			$userPreference->playReminder = $playReminder;
			$userPreference->save();
			return Redirect::to('profile');
		}
	}
	
	public function saveSectionSettings() {
		$attribute = Input::get('attribute');
		$input = Input::get('input');
		$userId = Auth::user()->get()->id;
		//Check if this user already has preferences set
		if(UserPreference::where('user_id',$userId)->first()){
			//edit the old model
			$userPreference = UserPreference::where('user_id',$userId)->first();
			$userPreference->$attribute = $input;
			$userPreference->save();
		} else {
			//make a new model
			$userPreference = new UserPreference();
			//set all notifications to the highest setting
			$userPreference->campaignsNotification = 'immediately';
			$userPreference->newsNotification = 'immediately';
			$userPreference->playReminder = 'daily';
			$userPreference->user_id = $userId;
			$userPreference->$attribute = $input;
			$userPreference->save();
		}
	}
}