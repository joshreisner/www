<?php

class HomeController extends BaseController {

	public function getIndex() {
		timeline::add(time(), 'about', 'About', '', 
			'
              	<p>I build websites. With <a href="http://katehowemakesthings.com/">Kate Howe</a>, I formed <a href="http://left-right.co/">Left&ndash;Right</a>, a web-development practice serving social-purpose clients. Formerly I was Director of Web Development at <a href="http://www.bureaublank.com/">Bureau Blank</a>, a branding agency in New York City, where I supervised work for clients such as Living Cities, the Harvard Kennedy School of Government, and PolicyLink.</p>
              	<p>This site merges information from 
                    <a href="https://twitter.com/joshreisner">Twitter</a>, 
                    <a href="http://instagram.com/joshreisner">Instagram</a>, 
                    <a href="https://foursquare.com/user/44810174">Foursquare</a>, 
                    <a href="https://vimeo.com/joshreisner">Vimeo</a>, 
                    <a href="http://last.fm/user/joshreisner">Last.fm</a> and
                    <a href="https://www.readability.com/joshreisner/">Readability</a>
                    with some info I enter into a custom CMS. I made it in PHP using Laravel, Isotope, and Bootstrap. <a href="http://github.com/joshreisner/www">View source on Github</a>.
                </p>
                <p>
                    <button type="button" class="btn btn-default"><a href="tel:9172848483"><i class="glyphicon glyphicon-earphone"></i></a></button>
                    <button type="button" class="btn btn-default"><a href="mailto:josh@joshreisner.com"><i class="glyphicon glyphicon-send"></i></a></button>
                </p>
			'
		);


		$media = array(array(
			'title'=>'About',
			'count'=>1,
			'class'=>'about',
		));

		//articles
		$articles = Article::all();
		foreach ($articles as $article) {
			$url = parse_url($article->url);
			if (substr($url['host'], 0, 4) == 'www.') $url['host'] = substr($url['host'], 4);
			$time = strtotime($article->date);
			timeline::add($time, 'article', 'Article', 'Published on ' . $url['host'] . ' ' . date('M j, Y', $time), 
				'<h4><a href="' . $article->url . '">' . $article->title . '</a></h4>' . 
				'<p>' . $article->excerpt . '</p>'
			);
		}
		$media[] = array(
			'title'=>'Articles',
			'count'=>count($articles),
			'class'=>'article',
		);

		//checkins
		$checkins = Checkin::all();
		foreach ($checkins as $checkin) {
			$time = strtotime($checkin->date);
			timeline::add($time, 'checkin', 'Check-in', 'Checked in on Foursquare ' . date('M j, Y', $time), 
				'<a class="image" href="#"><img src="http://maps.googleapis.com/maps/api/staticmap?center=' . $checkin->longitude . ',' . $checkin->latitude . '&zoom=13&maptype=terrain&size=640x380&sensor=false" width="640" height="380" class="img-responsive"></a>
				<p>' . $checkin->name . '</p>'
			);
		}
		$media[] = array(
			'title'=>'Check-ins',
			'count'=>count($checkins),
			'class'=>'checkin',
		);

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

		//projects
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

		//videos
		$videos = Video::all();
		foreach ($videos as $video) {
			$time = strtotime($video->date);
			timeline::add($time, 'video', 'Video', 'Liked on Vimeo ' . date('M j, Y', $time), 
				'<a class="image" href="' . $video->url . '"><img src="' . $video->img . '" width="640" height="' . $video->height . '" class="img-responsive"><i class="glyphicon glyphicon-play"></i></a>
				<p><a href="' . $video->url . '">' . $video->title . '</a> by ' . $video->author . '</p>'
			);
		}
		$media[] = array(
			'title'=>'Videos',
			'count'=>count($videos),
			'class'=>'video',
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
		return array_slice($articles, 0, 30);
	}
}