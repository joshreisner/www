<?php

Route::get('/', 'HomeController@index');

Route::get('/category/work', function(){
	return Redirect::to('/#projects', 301);
});

Route::get('/work', function(){
	return Redirect::to('/#projects', 301);
});

Route::group(['middleware'=>'auth', 'prefix'=>'import'], function(){
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

Route::post('/contact-form', function(){
    $validator = Validator::make(Request::all(), [
        'email' => 'required|email',
        'message' => 'required',
    ]);
    
	if ($validator->fails()) return;
	
	Mail::send('emails.message', ['content'=>nl2br(Request::input('message'))], function($message)
	{
	    $message
	    	->to('josh@joshreisner.com', 'Josh Reisner')
	    	->subject('JRDC Website Contact Form')
			->replyTo(Request::input('email'));
	});
});

Route::get('test/error', function(){
	trigger_error('test error!');
});
