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

Route::get('/', function()
{

	$media = array(array(
		'title'=>'About',
		'count'=>1,
		'class'=>'about',
	));

	//music
	$music = DB::table('music')->get();
	foreach ($music as $m) {
		$time = strtotime($m->updated);
		timeline::add($time, 'music', 'Favorite Music', 'Liked on Last.fm ' . date('M j, Y', $time), 
			'<a class="image" href="' . $m->url . '"><img src="' . $m->img . '" width="400" height="400" class="img-responsive"></a>
			<p>' . $m->artist . ': <a class="track" href="' . $m->url . '">' . $m->song . '</a></p>');
	}
	$media[] = array(
		'title'=>'Favorite Music',
		'count'=>count($music),
		'class'=>'music',
	);

	//twitter
	$statuses = DB::table('status')->get();
	foreach ($statuses as $status) {
		$time = strtotime($status->updated);
		timeline::add($time, 'status', 'Status Update', 'Tweeted on ' . date('M j, Y', $time), 
			'<p>' . $status->status . '</p>');
	}
	$media[] = array(
		'title'=>'Status Updates',
		'count'=>count($status),
		'class'=>'status',
	);

	//instagram
	$photos = DB::table('photo')->get();
	foreach ($photos as $photo) {
		$time = strtotime($photo->updated);
		timeline::add($time, 'photo', 'Photo', 'Taken on ' . date('M j, Y', $time), 
			'<a class="image" href="' . $m->url . '"><img src="' . $photo->img . '" class="img-responsive"></a>'
		);
	}
	$media[] = array(
		'title'=>'Photos',
		'count'=>count($photo),
		'class'=>'photo',
	);

	//work
	$work = DB::table('work')->get();
	foreach ($work as $w) {
		$time = strtotime($w->updated);
		timeline::add($time, 'work', 'Recent Work', 'Launched ' . date('M j, Y', $time), 
			'<a class="image" href="' . $w->url . '"><img src="' . $w->img . '" width="1440" height="900" class="img-responsive"></a>' . $w->description
		);
	}
	$media[] = array(
		'title'=>'Recent Work',
		'count'=>count($work),
		'class'=>'work',
	);

	return View::make('home', array(
		'articles'=>timeline::out(),
		'media'=>$media,
	));
});

class timeline {
	private static $timeline = array();

	static function add($time, $type, $header, $footer, $content) {
		self::$timeline[$time] = array('type'=>$type, 'header'=>$header, 'content'=>$content, 'footer'=>$footer);
	}

	static function out() {
		krsort(self::$timeline);
		$articles = array();
		foreach (self::$timeline as $time=>$article) $articles[] = $article;
		return $articles;
	}
}