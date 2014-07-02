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
Route::get ('logout', 'LoginController@requestLogout');

// Game logic
Route::get('game_menu', 'GameController@listGames');
