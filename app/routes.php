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

	Route::get('/lastfm', 'ImportController@getLastFm');

	Route::get('/instagram', 'ImportController@getInstagram');

	Route::get('/readability', 'ImportController@getReadability');

	Route::get('/twitter', 'ImportController@getTwitter');

	Route::get('/vimeo', 'ImportController@getVimeo');

	Route::get('/foursquare', 'ImportController@getFoursquare');

	Route::get('/goodreads', 'ImportController@getGoodreads');

	Route::get('/youtube', 'ImportController@getYouTube');

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



