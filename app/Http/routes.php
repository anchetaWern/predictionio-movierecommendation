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



$app->get('/', 'HomeController@index');

$app->get('/movies/import', 'AdminController@importMovies');

$app->post('/movie/random', 'HomeController@randomMovie');

$app->get('/movies/recommended', 'HomeController@recommendedMovies');

