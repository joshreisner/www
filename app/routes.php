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
	Route::get('error', function(){
		trigger_error('Test error you guys');
	});
	
	Route::group(array('prefix' => 'import'), function(){

		$services = [
			//'facebook'=>	'Facebook',
			'foursquare'=>	'Foursquare',
			'goodreads'=>	'Goodreads',
			'instagram'=>	'Instagram',
			'instapaper'=>	'Instapaper',
			'lastfm'=>		'LastFm',
			'readability'=>	'Readability',
			'soundcloud'=>	'SoundCloud',
			'twitter'=>		'Twitter',
			'vimeo'=>		'Vimeo',
			'youtube'=>		'YouTube',
		];

		Route::get('/', function() use ($services){
			return View::make('import')->with('services', $services);
		});

		foreach ($services as $key=>$value) {
			Route::get('/' . $key, 	'ImportController@get' . $value);
		}
	});
});

App::missing(function($exception) {
	return Redirect::to('/', 301); 
});



