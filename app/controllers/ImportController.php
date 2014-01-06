<?php

class ImportController extends BaseController {

	public function getFacebook() {

		if (Session::has('facebook.access_token')) {

			if (!$file = file_get_contents('https://graph.facebook.com/me/posts?with=location&limit=25&access_token=' . Session::get('facebook.access_token'))) {
				trigger_error('Facebook API call did not work!');
			}

			$facebook = json_decode($file);

			foreach ($facebook->data as $fbcheckin) {

				//echo $fbcheckin->id . '<br>';

				$date = new DateTime;
				$date->setTimestamp(strtotime($fbcheckin->created_time));

				if (Checkin::where('date', '=', $date)->count()) continue;
				
				$checkin 				= new Checkin;
				$checkin->name 	 		= $fbcheckin->message . ' at ' . $fbcheckin->place->name;
				$checkin->date 			= $date;
				$checkin->latitude 		= $fbcheckin->place->location->latitude;
				$checkin->longitude 	= $fbcheckin->place->location->longitude;
				$checkin->updated 		= new DateTime;
				$checkin->source 		= 'Facebook';
				$checkin->updater 		= 1;
				$checkin->active 		= 1;
				$checkin->precedence 	= Checkin::max('precedence') + 1;
				$checkin->save();
			}

			DB::table('avalon_objects')->where('id', 8)->update(array(
				'updated'	=>new DateTime,
				'updater'	=>1,
				'count'		=>Checkin::active()->count(),
			));

			echo '<pre>', print_r($facebook);
			return @Kint::dump($facebook);


		} elseif (Input::has('code')) {

			//curl exchange the code for an access token
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token' .
				'?client_id=' . Config::get('api.facebook.key') . 
				'&redirect_uri=' . urlencode(Request::url()) . 
				'&client_secret=' . Config::get('api.facebook.secret') . 
				'&code=' . Input::get('code'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			if (strstr($result, 'error')) {
				echo $result;
				exit;
			} else {
				parse_str($result, $parts);
				Session::put('facebook.access_token', $parts['access_token']);
				return Redirect::to(Request::url());
			}
		} else {
			return Redirect::to('https://www.facebook.com/dialog/oauth' . 
				'?client_id=' . Config::get('api.facebook.key') . 
				'&redirect_uri=' . urlencode(Request::url()) . 
				'&state=' . md5(uniqid(mt_rand(), true)) . 
				'&scope=read_stream');
		}
	}

	public function getFoursquare() {

		if (!$file = file_get_contents('https://feeds.foursquare.com/history/' . Config::get('api.foursquare') . '.kml')) {
			trigger_error('Foursquare API call did not work!');
		}

		$checkins = simplexml_load_string($file);

		//DB::table('checkins')->truncate();

		foreach ($checkins->Folder->Placemark as $placemark) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($placemark->published));

			if (Checkin::where('date', '=', $date)->count()) continue;
			
			list($longitude, $latitude) = explode(',', $placemark->Point->coordinates);

			$checkin 				= new Checkin;
			$checkin->name 	 		= $placemark->name;
			$checkin->date 			= $date;
			$checkin->latitude 		= $latitude;
			$checkin->longitude 	= $longitude;
			$checkin->updated 		= new DateTime;
			$checkin->source 		= 'Foursquare';
			$checkin->updater 		= 1;
			$checkin->active 		= 1;
			$checkin->precedence 	= Checkin::max('precedence') + 1;
			$checkin->save();
		}

		DB::table('avalon_objects')->where('id', 8)->update(array(
			'updated'	=>new DateTime,
			'updater'	=>1,
			'count'		=>Checkin::active()->count(),
		));

		//echo '<pre>', print_r($checkins);
		return @Kint::dump($checkins);
	}

	public function getGoodreads() {
		if (!$file = file_get_contents('https://www.goodreads.com/review/list_rss/9112494?key=a9d74013bd7f71963fa627b54e49f3a3856b1108&shelf=%23READ%23')) {
			trigger_error('Goodreads API call did not work!');
		}

		DB::table('books')->truncate();

		$precedence = 1;

		$goodreads = simplexml_load_string($file);

		foreach ($goodreads->channel->item as $goodread) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($goodread->user_read_at));

			$book 				= new Book;
			$book->title 		= $goodread->title;
			$book->author 	 	= $goodread->author_name;
			$book->published 	= $goodread->book_published;
			$book->img 			= $goodread->book_medium_image_url;
			$book->url 			= $goodread->link;
			$book->date 		= $date;
			$book->updated 		= new DateTime;
			$book->updater 		= 1;
			$book->active 		= 1;
			$book->precedence 	= $precedence++;
			$book->save();
		}

		DB::table('avalon_objects')->where('id', 10)->update(array(
			'updated'	=>new DateTime,
			'updater'	=>1,
			'count'		=>--$precedence,
		));

		//echo '<pre>', print_r($goodreads);
		return @Kint::dump($goodreads);
	}

	public function getReadability() {
		if (!$file = file_get_contents('https://www.readability.com/joshreisner/favorites/feed')) {
			trigger_error('Readability API call did not work!');
		}

		DB::table('articles')->truncate();

		$precedence = 1;

		$readability = simplexml_load_string($file);

		foreach ($readability->channel->item as $rdbl) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($rdbl->pubDate));

			$article 				= new Article;
			$article->title 		= $rdbl->title;
			$article->date 			= $date;
			$article->excerpt 		= $rdbl->description;
			$article->url 		 	= substr($rdbl->link, 36);
			$article->updated 		= new DateTime;
			$article->updater 		= 1;
			$article->active 		= 1;
			$article->precedence 	= $precedence++;
			$article->save();
		}

		DB::table('avalon_objects')->where('id', 9)->update(array(
			'updated'	=>new DateTime,
			'updater'	=>1,
			'count'		=>--$precedence,
		));

		return @Kint::dump($readability);
	}

	public function getYouTube() {
		if (!$file = file_get_contents('http://gdata.youtube.com/feeds/api/users/joshreisner/favorites?max-results=50&alt=json')) {
			trigger_error('YouTube API call did not work!');
		}

		$file = str_replace('$', '', $file);

		$youtube = json_decode($file);

		foreach ($youtube->feed->entry as $video) {
			if (!isset($video->mediagroup->mediathumbnail)) continue;
			echo $video->title->t . '<br>';
			echo $video->link[0]->href . '<br>';
			echo $video->published->t . '<br>';
			echo $video->mediagroup->mediathumbnail[0]->url . '<br>';
			echo '<br>';
		}

		echo '<pre>', print_r($youtube);
	}

	public function getVimeo() {
		if (!$file = file_get_contents('http://vimeo.com/api/v2/joshreisner/likes.json')) {
			trigger_error('Vimeo API call did not work!');
		}

		DB::table('videos')->truncate();

		$precedence = 1;

		$vimeos = json_decode($file);

		foreach ($vimeos as $vimeo) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($vimeo->liked_on));

			$video 				= new Video;
			$video->title 	 	= $vimeo->title;
			$video->url 		= $vimeo->url;
			$video->date 		= $date;
			$video->author 		= $vimeo->user_name;
			$video->img 		= $vimeo->thumbnail_large;
			$video->height 		= (640 / $vimeo->width) * $vimeo->height; //thumbnail height
			$video->updated 	= new DateTime;
			$video->updater 	= 1;
			$video->active 		= 1;
			$video->precedence 	= $precedence++;
			$video->save();
		}

		DB::table('avalon_objects')->where('id', 7)->update(array(
			'updated'	=>new DateTime,
			'updater'	=>1,
			'count'		=>--$precedence,
		));

		return @Kint::dump($vimeos);
	}

	public function getTwitter() {

		$twitter = new TwitterAPIExchange(array(
			'consumer_key' 				=> Config::get('api.twitter.consumer_key'),
			'consumer_secret' 			=> Config::get('api.twitter.consumer_secret'),
			'oauth_access_token' 		=> Config::get('api.twitter.access_token'),
			'oauth_access_token_secret'	=> Config::get('api.twitter.access_token_secret'),
		));

		if (!$file = $twitter->setGetfield('?username=joshreisner&exclude_replies=true')
			->buildOauth('https://api.twitter.com/1.1/statuses/user_timeline.json', 'GET')
			->performRequest()) {
			trigger_error('Twitter API call did not work!');
		}

		DB::table('tweets')->truncate();

		$precedence = 1;

		$tweets = json_decode($file);

		foreach ($tweets as $tweet) {
			if (!empty($tweet->entities->urls)) {
				foreach ($tweet->entities->urls as $url) {
					$tweet->text = str_replace($url->url, '<a href="' . $url->expanded_url . '">' . $url->display_url . '</a>', $tweet->text);
				}
			}

			if (!empty($tweet->entities->user_mentions)) {
				foreach ($tweet->entities->user_mentions as $user) {
					$tweet->text = str_replace('@' . $user->screen_name, '<a href="https://twitter.com/' . $user->screen_name . '">@' . $user->screen_name . '</a>', $tweet->text);
				}
			}

			$date = new DateTime;
			$date->setTimestamp(strtotime($tweet->created_at));

			$status 				= new Tweet;
			$status->text 	 		= $tweet->text;
			$status->date 			= $date;
			$status->updated 		= new DateTime;
			$status->updater 		= 1;
			$status->active 		= 1;
			$status->precedence 	= $precedence++;
			$status->save();
		}

		DB::table('avalon_objects')->where('id', 6)->update(array(
			'updated'	=>new DateTime,
			'updater'	=>1,
			'count'		=>--$precedence,
		));

		return @Kint::dump($tweets);
	}

	public function getInstagram() {

		if (!$file = file_get_contents('https://api.instagram.com/v1/users/6676862/media/recent/?access_token=' . Config::get('api.instagram.token'))) {
			trigger_error('Instagram API call did not work!');
		}

		DB::table('photos')->truncate();

		$photos = json_decode($file);
		$precedence = 1;
		foreach ($photos->data as $pic) {
			$date = new DateTime;
			$date->setTimestamp($pic->created_time);

			$photo 				= new Photo;
			$photo->location	= $pic->location->name;
			$photo->img 		= $pic->images->standard_resolution->url;
			$photo->width 		= $pic->images->standard_resolution->width;
			$photo->height 		= $pic->images->standard_resolution->height;
			$photo->url 		= $pic->link;
			$photo->date 		= $date;
			$photo->updated 	= new DateTime;
			$photo->updater 	= 1;
			$photo->active 		= 1;
			$photo->precedence 	= $precedence++;
			$photo->save();
		}

		DB::table('avalon_objects')->where('id', 4)->update(array(
			'updated'	=>new DateTime,
			'updater'	=>1,
			'count'		=>--$precedence,
		));

		return @Kint::dump($photos);
	}

	public function getLastFm() {

		//retrieve last.fm chart infoz
		if (!$file = file_get_contents('http://ws.audioscrobbler.com/2.0/?method=user.getLovedTracks&user=joshreisner&api_key=' . Config::get('api.lastfm') . '&period=7day&format=json')) {
			trigger_error('Last.fm API call did not work!');
		}

		DB::table('songs')->truncate();

		//clean up XML
		$file = str_replace('#text', 'text', $file);
		$file = str_replace('@attr', 'attr', $file);

		$tracks = json_decode($file);
		$precedence = 1;
		foreach ($tracks->lovedtracks->track as $track) {

			if (!isset($track->image)) continue;

			$date = new DateTime;
			$date->setTimestamp($track->date->uts);

			$song 				= new Song;
			$song->song 		= $track->name;
			$song->url 			= $track->url;
			$song->artist 		= $track->artist->name;
			$song->date 		= $date;
			$song->img 			= $track->image[3]->text;
			$song->updated 		= new DateTime;
			$song->updater 		= 1;
			$song->active 		= 1;
			$song->precedence 	= $precedence++;
			$song->save();
		}

		DB::table('avalon_objects')->where('id', 2)->update(array(
			'updated'=>new DateTime,
			'updater'=>1,
			'count'=>--$precedence,
		));

		return @Kint::dump($tracks);
	}
}