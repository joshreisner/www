<?php

class HomeController extends BaseController {

	private static $timeline 	= array();
	private static $media 		= array();
	private static $count 		= 25; //# articles per page

	public function getIndex() {
		return View::make('home', array(
			'articles'=>self::timeline(),
			'media'=>self::$media,
		));
	}

	public function getMore($offset) {
		return json_encode(self::timeline($offset));
	}

	private static function timeline($offset=0) {

		//about
		self::timelineAdd(time(), 'about', 'About', '', '
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
            </p>'
		);
		self::$media[] = array(
			'title'=>'About',
			'count'=>1,
			'class'=>'about',
		);


		//check-ins
		$checkins = Checkin::all();
		foreach ($checkins as $checkin) {
			$time = strtotime($checkin->date);
			self::timelineAdd($time, 'checkin', 'Check-in', 'Foursquare', 
				'<a class="image" href="#"><img src="http://maps.googleapis.com/maps/api/staticmap?center=' . $checkin->longitude . ',' . $checkin->latitude . '&zoom=13&maptype=terrain&size=640x380&sensor=false" width="640" height="380" class="img-responsive"></a>
				<p>' . $checkin->name . '</p>'
			);
		}
		self::$media[] = array(
			'title'=>'Check-ins',
			'count'=>count($checkins),
			'class'=>'checkin',
		);


		//music
		$songs = Song::all();
		foreach ($songs as $song) {
			$time = strtotime($song->date);
			self::timelineAdd($time, 'music', 'Music', 'Last.fm', 
				'<a class="image" href="' . $song->url . '"><img src="' . $song->img . '" width="300" height="300" class="img-responsive"></a>
				<p>' . $song->artist . ': <a class="track" href="' . $song->url . '">' . $song->song . '</a></p>');
		}
		self::$media[] = array(
			'title'=>'Music',
			'count'=>count($songs),
			'class'=>'music',
		);


		//photos
		$photos = Photo::all();
		foreach ($photos as $photo) {
			$time = strtotime($photo->date);
			self::timelineAdd($time, 'photo', 'Photo', 'Instagram', 
				'<a class="image" href="' . $photo->url . '"><img src="' . $photo->img . '" width="' . $photo->width . '" height="' . $photo->height . '" class="img-responsive"></a>
				<p>' . $photo->location . '</p>'
			);
		}
		self::$media[] = array(
			'title'=>'Photos',
			'count'=>count($photos),
			'class'=>'photo',
		);


		//projects
		$projects = Project::all();
		foreach ($projects as $project) {
			$time = strtotime($project->date);
			self::timelineAdd($time, 'work', 'Project', self::domain($project->url), 
				'<a class="image" href="' . $project->url . '"><img src="' . $project->img . '" width="640" height="400" class="img-responsive"></a>' . $project->description
			);
		}
		self::$media[] = array(
			'title'=>'Projects',
			'count'=>count($projects),
			'class'=>'work',
		);


		//recommended reading
		$articles = Article::all();
		foreach ($articles as $article) {
			$time = strtotime($article->date);
			self::timelineAdd($time, 'article', 'Recommended Reading', self::domain($article->url), 
				'<h4><a href="' . $article->url . '">' . $article->title . '</a></h4>' . 
				'<p>' . $article->excerpt . '</p>'
			);
		}
		self::$media[] = array(
			'title'=>'Rec. Reading',
			'count'=>count($articles),
			'class'=>'article',
		);


		//tweets
		$statuses = Tweet::all();
		foreach ($statuses as $status) {
			$time = strtotime($status->date);
			self::timelineAdd($time, 'status', 'Tweet', 'Twitter', 
				'<p>' . $status->text . '</p>');
		}
		self::$media[] = array(
			'title'=>'Tweets',
			'count'=>count($statuses),
			'class'=>'status',
		);


		//videos
		$videos = Video::all();
		foreach ($videos as $video) {
			$time = strtotime($video->date);
			self::timelineAdd($time, 'video', 'Video', 'Vimeo', 
				'<a class="image" href="' . $video->url . '"><img src="' . $video->img . '" width="640" height="' . $video->height . '" class="img-responsive"><i class="glyphicon glyphicon-play"></i></a>
				<p><a href="' . $video->url . '">' . $video->title . '</a> by ' . $video->author . '</p>'
			);
		}
		self::$media[] = array(
			'title'=>'Videos',
			'count'=>count($videos),
			'class'=>'video',
		);

		krsort(self::$timeline);
		$keys = array_slice(array_keys(self::$timeline), $offset, self::$count);
		return array_intersect_key(self::$timeline, array_flip($keys));
	}

	private static function domain($url) {
		$url = parse_url($url);
		if (substr($url['host'], 0, 4) == 'www.') return substr($url['host'], 4);
		return $url['host'];
	}

	private static function timelineAdd($time, $type, $header, $source, $content) {
		self::$timeline[$time] = array('type'=>$type, 'header'=>$header, 'content'=>$content, 'source'=>$source);
	}
}