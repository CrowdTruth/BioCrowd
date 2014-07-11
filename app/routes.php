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

Route::get('/', array('before' => 'auth', function()
{
	return View::make('home');
}));

Route::get ('login' , 'LoginController@requestLogin');
Route::post('login' , 'LoginController@doLogin');
Route::post('register' , 'LoginController@doRegister');
Route::get ('logout', 'LoginController@requestLogout');

// Game logic
Route::get('game_menu', 'GameListController@listGames');

// Game mechanics added 'on the flight'
//   Each game mechanics will need:
//   a URL        -- URL to map (Matching URL's provided by GameListController@listGames
//   any VIER declared by the controller
//   a CONTROLLER -- A controller class / function
$url = 'factspan';
$controller = 'FactorSpanController@playGame';
Route::get($url, $controller);
