<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'GameListController@listGames', [ 'before' => 'auth', function()
{
	return View::make('home');
} ] );

Route::get ('login' , 'LoginController@requestLogin');
Route::post('login' , 'LoginController@doLogin');
Route::post('register' , 'LoginController@doRegister');
Route::get ('logout', 'LoginController@requestLogout');

Route::post('changePass' , 'ProfileController@changePassword');
Route::post('editProfile', 'ProfileController@editProfile');

Route::get('profile' , 'ProfileController@getView');

// Game logic
Route::get('home', 'GameListController@listGames');

// Campaign logic
Route::get('campaignMenu', 'CampaignListController@listCampaigns');

// Game mechanics added 'on the flight'
//   Each game mechanics will need:
//   a URL        -- URL to map (Matching URL's provided by GameListController@listGames
//   any VIEW declared by the controller
//   a CONTROLLER -- A controller class / function
Route::any('playGame', 'GameController@playGame');

Route::any('submitGame', 'GameController@submitGame');

Route::any('playCampaign', 'CampaignController@playCampaign');
Route::any('submitCampaign', 'CampaignController@submitCampaign');

// Administrator module routes -- maybe even put in another file ?
Route::controller('admin', 'AdminController');
Route::controller('admin-export', 'DataportController');
Route::controller('admin-games', 'GameAdminController');

Route::get('test', function() {
	return View::make('test');
});
