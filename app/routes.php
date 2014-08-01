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

Route::get('/category/work', function(){
	return Redirect::to('/#project');
});

Route::get('/work', function(){
	return Redirect::to('/#project');
});

Route::group(['filter'=>'auth'], function(){

	Route::group(array('prefix' => 'import'), function(){
		Route::get('/facebook',		'ImportController@getFacebook');
		Route::get('/foursquare',	'ImportController@getFoursquare');
		Route::get('/goodreads',	'ImportController@getGoodreads');
		Route::get('/instagram',	'ImportController@getInstagram');
		Route::get('/instapaper',	'ImportController@getInstapaper');
		Route::get('/lastfm',		'ImportController@getLastFm');
		Route::get('/readability',	'ImportController@getReadability');
		Route::get('/soundcloud',	'ImportController@getSoundcloud');
		Route::get('/twitter',		'ImportController@getTwitter');
		Route::get('/vimeo',		'ImportController@getVimeo');
		Route::get('/youtube',		'ImportController@getYouTube');
	});

});

App::missing(function($exception) {
	return Redirect::to('/', 301); 
});



