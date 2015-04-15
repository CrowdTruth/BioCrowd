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

Route::get('/', [ 'before' => 'auth', function()
{
	return View::make('home');
} ] );

Route::get ('login' , 'LoginController@requestLogin');
Route::post('login' , 'LoginController@doLogin');
Route::post('register' , 'LoginController@doRegister');
Route::get ('logout', 'LoginController@requestLogout');

// Game logic
Route::get('gameMenu', 'GameListController@listGames');

// Campaign logic
Route::get('campaignMenu', 'CampaignListController@listCampaigns');

// Game mechanics added 'on the flight'
//   Each game mechanics will need:
//   a URL        -- URL to map (Matching URL's provided by GameListController@listGames
//   any VIEW declared by the controller
//   a CONTROLLER -- A controller class / function
Route::get('playGame', 'GameController@playGame');
Route::post('submitGame', 'GameController@submitGame');

Route::get('playCampaign', 'CampaignController@playCampaign');
Route::post('submitCampaign', 'CampaignController@submitCampaign');

// Administrator module routes -- maybe even put in another file ?
Route::get ('admin/login' , 'AdminController@requestLogin');
Route::post('admin/login' , 'AdminController@doLogin');
Route::get('admin/logout' , 'AdminController@requestLogout');

Route::get ('admin', [ 'before' => 'adminauth', 'uses' => 'AdminController@home' ] );

Route::get('admin/listuser', [ 'before' => 'adminauth', 'uses' => 'AdminController@listUsersView' ] );

Route::get('admin/createuser', [ 'before' => 'adminauth', 'uses' => 'AdminController@newUserView' ] );

Route::get('admin/listGames', [ 'before' => 'adminauth', 'uses' => 'AdminController@listGamesView' ] );

Route::get('admin/editGame', [ 'before' => 'adminauth', 'uses' => 'AdminController@editGameView' ] );
Route::post	('admin/editGame', [ 'before' => 'adminauth', 'uses' => 'AdminController@editGameAction' ] );

Route::get('admin/listGameTypes', [ 'before' => 'adminauth', 'uses' => 'AdminController@listGameTypesView' ] );
Route::get('admin/listGameTypesAction', [ 'before' => 'adminauth', 'uses' => 'AdminController@listGameTypesAction' ] );

Route::get('admin/exportFile', [ 'before' => 'adminauth', 'uses' => 'DataportController@exportToFileView' ] );
Route::post('admin/exportFile', [ 'before' => 'adminauth', 'uses' => 'DataportController@exportToFile' ] );
Route::get('admin/exportWebhook', [ 'before' => 'adminauth', 'uses' => 'DataportController@webhook' ] );

Route::get('test', function() {
	return View::make('test');
});
