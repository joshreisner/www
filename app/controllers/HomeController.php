<?php

class HomeController extends BaseController {

	private static $timeline 	= array();
	private static $types 		= array();

	public function getIndex() {
		self::timeline();
		return View::make('home', array(
			'types'=>self::$types,
		));
	}

	public function getData() {
		//remove time keys for javascript
		$articles = self::timeline();
		$output = array();
		foreach ($articles as $time=>$article) {
			$article['time'] = $time;
			$output[] = $article;
		}
		return json_encode($output);
	}

	private static function timeline() {

		//about
		self::$types[] = array(
			'title'=>'About',
			'count'=>1,
			'class'=>'about',
		);


		//books
		$books = Book::all();
		foreach ($books as $book) {
			$time = strtotime($book->date);
			self::timelineAdd($time, 'book', 'Book I Read', 'Goodreads', 
				'<a href="' . $book->url . '"><img src="' . $book->img . '"></a>
				<p>' . $book->author . '<br><a href="' . $book->url . '">' . $book->title . '</a><br>' . $book->published . '</p>'
			);
		}
		self::$types[] = array(
			'title'=>'Books',
			'count'=>count($books),
			'class'=>'book',
		);


		//music
		$songs = Song::all();
		foreach ($songs as $song) {
			$time = strtotime($song->date);
			self::timelineAdd($time, 'music', 'Music', 'Last.fm', 
				'<a class="image" href="' . $song->url . '"><img src="' . $song->img . '" width="300" height="300" class="img-responsive"></a>
				<p>' . $song->artist . ': <a class="track" href="' . $song->url . '">' . $song->song . '</a></p>');
		}
		self::$types[] = array(
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
		self::$types[] = array(
			'title'=>'Photos',
			'count'=>count($photos),
			'class'=>'photo',
		);


		//places visited
		$checkins = Checkin::all();
		foreach ($checkins as $checkin) {
			$time = strtotime($checkin->date);
			self::timelineAdd($time, 'checkin', 'Place Visited', $checkin->source, 
				'<a class="image" href="#"><img src="http://maps.googleapis.com/maps/api/staticmap?center=' . $checkin->latitude . ',' . $checkin->longitude . '&zoom=13&maptype=terrain&size=640x380&sensor=false" width="640" height="380" class="img-responsive"></a>
				<p>' . $checkin->name . '</p>'
			);
		}
		self::$types[] = array(
			'title'=>'Places Visited',
			'count'=>count($checkins),
			'class'=>'checkin',
		);


		//projects
		$projects = Project::all();
		foreach ($projects as $project) {
			$time = strtotime($project->date);
			self::timelineAdd($time, 'project', 'Project', self::domain($project->url), 
				'<a class="image" href="' . $project->url . '"><img src="' . $project->img . '" width="640" height="400" class="img-responsive"></a>' . $project->description
			);
		}
		self::$types[] = array(
			'title'=>'Projects',
			'count'=>count($projects),
			'class'=>'project',
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
		self::$types[] = array(
			'title'=>'Rec. Reading',
			'count'=>count($articles),
			'class'=>'article',
		);


		//tweets
		$statuses = Tweet::all();
		foreach ($statuses as $status) {
			$time = strtotime($status->date);
			self::timelineAdd($time, 'status', 'Status Update', 'Twitter', 
				'<p>' . $status->text . '</p>');
		}
		self::$types[] = array(
			'title'=>'Status Updates',
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
		self::$types[] = array(
			'title'=>'Videos',
			'count'=>count($videos),
			'class'=>'video',
		);

		krsort(self::$timeline);
		return self::$timeline;
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