<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



$app->get('/', 'App\Http\Controllers\HomeController@index');

$app->get('/movies/import', 'App\Http\Controllers\AdminController@importMovies');

$app->post('/movie/random', 'App\Http\Controllers\HomeController@randomMovie');

$app->get('/movies/recommended', 'App\Http\Controllers\HomeController@recommendedMovies');

