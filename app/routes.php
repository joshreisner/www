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

Route::get('/data', 'HomeController@getData');

Route::get('/category/work', function() {
	return Redirect::to('/#project');
});

Route::get('/work', function() {
	return Redirect::to('/#project');
});

Route::group(array('prefix' => 'import'), function()
{

	Route::get('/facebook', 'ImportController@getFacebook'); //under const, oauth2
	Route::get('/foursquare', 'ImportController@getFoursquare');
	Route::get('/goodreads', 'ImportController@getGoodreads');
	Route::get('/instagram', 'ImportController@getInstagram');
	Route::get('/instapaper', 'ImportController@getInstapaper'); //under const
	Route::get('/lastfm', 'ImportController@getLastFm');
	Route::get('/readability', 'ImportController@getReadability');
	Route::get('/soundcloud', 'ImportController@getSoundCloud'); //under const, oauth2
	Route::get('/twitter', 'ImportController@getTwitter');
	Route::get('/vimeo', 'ImportController@getVimeo');
	Route::get('/youtube', 'ImportController@getYouTube'); //under const

	Route::get('/', function(){
		$importer = new ImportController;
		$importer->getLastFm();
		$importer->getInstagram();
		$importer->getReadability();
		$importer->getTwitter();
		$importer->getVimeo();
		$importer->getFoursquare();
		$importer->getGoodreads();
	});

});

App::missing(function($exception) {
	return Redirect::to('/', 301); 
});



