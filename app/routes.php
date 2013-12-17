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

Route::get('/', 'HomeController@getIndex');

Route::get('/import/lastfm', 'ImportController@getLastFm');

Route::get('/import/instagram', 'ImportController@getInstagram');

Route::get('/import/readability', 'ImportController@getReadability');

Route::get('/import/twitter', 'ImportController@getTwitter');

Route::get('/import/vimeo', 'ImportController@getVimeo');
