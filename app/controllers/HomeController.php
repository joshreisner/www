<?php

class HomeController extends BaseController {

	public function getIndex() {
		$media = array(array(
			'title'=>'About',
			'count'=>1,
			'class'=>'about',
		));

		//music
		$songs = Song::all();
		foreach ($songs as $song) {
			$time = strtotime($song->date);
			timeline::add($time, 'music', 'Music', 'Liked on Last.fm ' . date('M j, Y', $time), 
				'<a class="image" href="' . $song->url . '"><img src="' . $song->img . '" width="300" height="300" class="img-responsive"></a>
				<p>' . $song->artist . ': <a class="track" href="' . $song->url . '">' . $song->song . '</a></p>');
		}
		$media[] = array(
			'title'=>'Music',
			'count'=>count($songs),
			'class'=>'music',
		);

		//twitter
		$statuses = Tweet::all();
		foreach ($statuses as $status) {
			$time = strtotime($status->date);
			timeline::add($time, 'status', 'Tweet', 'Tweeted on ' . date('M j, Y', $time), 
				'<p>' . $status->text . '</p>');
		}
		$media[] = array(
			'title'=>'Tweets',
			'count'=>count($statuses),
			'class'=>'status',
		);

		//instagram
		$photos = Photo::all();
		foreach ($photos as $photo) {
			$time = strtotime($photo->date);
			timeline::add($time, 'photo', 'Photo', 'Added to Instagram on ' . date('M j, Y', $time), 
				'<a class="image" href="' . $photo->url . '"><img src="' . $photo->img . '" width="' . $photo->width . '" height="' . $photo->height . '" class="img-responsive"></a>
				<p>' . $photo->location . '</p>'
			);
		}
		$media[] = array(
			'title'=>'Photos',
			'count'=>count($photos),
			'class'=>'photo',
		);

		//work
		$projects = Project::all();
		foreach ($projects as $project) {
			$time = strtotime($project->date);
			timeline::add($time, 'work', 'Project', 'Launched ' . date('M j, Y', $time), 
				'<a class="image" href="' . $project->url . '"><img src="' . $project->img . '" width="640" height="400" class="img-responsive"></a>' . $project->description
			);
		}
		$media[] = array(
			'title'=>'Projects',
			'count'=>count($projects),
			'class'=>'work',
		);

		return View::make('home', array(
			'articles'=>timeline::out(),
			'media'=>$media,
		));
	}
}

class timeline {
	private static $timeline = array();

	static function add($time, $type, $header, $footer, $content) {
		self::$timeline[$time] = array('type'=>$type, 'header'=>$header, 'content'=>$content, 'footer'=>$footer);
	}

	static function out() {
		krsort(self::$timeline);
		$articles = array();
		foreach (self::$timeline as $time=>$article) $articles[] = $article;
		return array_slice($articles, 0, 40);
	}
}