<?php

Route::get('/', 'HomeController@index');

Route::get('/category/work', function(){
	return Redirect::to('/#projects', 301);
});

Route::get('/work', function(){
	return Redirect::to('/#projects', 301);
});

Route::group(['before'=>'auth', 'prefix'=>'import'], function(){
	$services = [
		'facebook'		=>'Facebook',
		'foursquare'	=>'FourSquare',
		'goodreads'		=>'GoodReads',
		'instagram'		=>'Instagram',
		'instapaper'	=>'Instapaper',
		'readability'	=>'Readability',
		'twitter'		=>'Twitter',
		'vimeo'			=>'Vimeo',
	];
	
	foreach ($services as $slug=>$service) {
		Route::get($slug, 'ApiController@' . $slug);
	}
	
	Route::get('/', function() use($services){
		return View::make('import', compact('services'));
	});
});

Route::post('/contact', function(){
	Mail::send('emails.message', ['content'=>nl2br(Input::get('message'))], function($message)
	{
	    $message
	    	->to('josh@joshreisner.com', 'Josh Reisner')
	    	->subject('JRDC Website Contact Page')
			->replyTo(Input::get('email'));
	});
});

App::missing(function($exception) {
	return Redirect::to('/', 301); 
});

