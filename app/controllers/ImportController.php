<?php

class ImportController extends BaseController {


	public function getFacebook() {

	    $oauth = OAuth::consumer('Facebook');

		if (Session::has('tokens.facebook')) {

	        $checkins = json_decode($oauth->request('/me/posts?with=location&limit=25'));
			$images = array();

			foreach ($checkins->data as $fbcheckin) {

				$date = new DateTime;
				$date->setTimestamp(strtotime($fbcheckin->created_time));

				if (!$checkin = Checkin::where('facebook_id', $fbcheckin->id)->first()) {
					$checkin = new Checkin;
				}
				
				$checkin->name 	 		= $fbcheckin->message . ' at ' . $fbcheckin->place->name;
				$checkin->date 			= $date;
				$checkin->latitude 		= $fbcheckin->place->location->latitude;
				$checkin->longitude 	= $fbcheckin->place->location->longitude;
				$checkin->source 		= 'Facebook';
				$checkin->facebook_id	= $fbcheckin->id;
				$checkin->created_at 	= new DateTime;
				$checkin->updated_at 	= new DateTime;
				$checkin->updated_by 	= 1;
				$checkin->precedence 	= Checkin::max('precedence') + 1;
				$checkin->save();
				
				//save image to database
				$image = file_get_contents(self::mapURL($checkin->latitude, $checkin->longitude));
				$image_props = Joshreisner\Avalon\AvalonServiceProvider::saveImage(57, $image, 'map', 'png', $checkin->id);

				if ($checkin->map_id !== null) $images[] = $checkin->map_id;

				$checkin->map_id 		= $image_props['file_id'];
				$checkin->save();

			}

			if (count($images)) {
				$images = DB::table('avalon_files')->whereIn('id', $images)->get();
				Joshreisner\Avalon\AvalonServiceProvider::cleanupFiles($images);
			}

			DB::table('avalon_objects')->where('id', 8)->update(array(
				'updated_at'=>new DateTime,
				'updated_by'	=>1,
				'count'		=>Checkin::count(),
			));

			return 'Facebook imported';

	    } elseif (Input::has('code')) {

			Session::put('tokens.facebook', $oauth->requestAccessToken(Input::get('code')));
			return Redirect::to(Request::url());

	    } else {
			return Redirect::to((string)$oauth->getAuthorizationUri());
	    }
	}

	public function getFoursquare() {

		if (!$file = file_get_contents('https://feeds.foursquare.com/history/' . Config::get('api.foursquare') . '.kml')) {
			trigger_error('Foursquare API call did not work!');
		}

		$checkins = simplexml_load_string($file);
		$images = array();
		//dd($checkins);

		//DB::table('checkins')->truncate();

		foreach ($checkins->Folder->Placemark as $placemark) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($placemark->published));

			list($longitude, $latitude) = explode(',', $placemark->Point->coordinates);

			if (!$checkin = Checkin::whereNull('facebook_id')
					->where('latitude', $latitude)
					->where('longitude', $longitude)
					->first()) {
				$checkin 			= new Checkin;
			}
			$checkin->name 	 		= $placemark->name;
			$checkin->date 			= $date;
			$checkin->latitude 		= $latitude;
			$checkin->longitude 	= $longitude;
			$checkin->source 		= 'Foursquare';
			$checkin->created_at 	= new DateTime;
			$checkin->updated_at 	= new DateTime;
			$checkin->updated_by 	= 1;
			$checkin->precedence 	= Checkin::max('precedence') + 1;
			$checkin->save();
			
			//save image to database
			$image = file_get_contents(self::mapURL($checkin->latitude, $checkin->longitude));
			$image_props = Joshreisner\Avalon\AvalonServiceProvider::saveImage(57, $image, 'map', 'png', $checkin->id);

			if ($checkin->map_id !== null) $images[] = $checkin->map_id;

			$checkin->map_id 		= $image_props['file_id'];
			$checkin->save();

		}

		if (count($images)) {
			$images = DB::table('avalon_files')->whereIn('id', $images)->get();
			Joshreisner\Avalon\AvalonServiceProvider::cleanupFiles($images);
		}

		DB::table('avalon_objects')->where('id', 8)->update(array(
			'updated_at'	=>new DateTime,
			'updated_by'	=>1,
			'count'			=>Checkin::count(),
		));

		return 'foursquare imported';
	}

	public function getGoodreads() {
		if (!$file = file_get_contents('https://www.goodreads.com/review/list_rss/9112494?key=a9d74013bd7f71963fa627b54e49f3a3856b1108&shelf=%23READ%23')) {
			trigger_error('Goodreads API call did not work!');
		}

		$goodreads = simplexml_load_string($file);
		//dd($goodreads);

		//DB::table('books')->truncate();
		//DB::table('avalon_files')->truncate();

		$precedence = 1;
		$images = array();

		foreach ($goodreads->channel->item as $goodread) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($goodread->user_read_at));

			if (!$book = Book::where('goodreads_id', $goodread->book_id)->first()) {
				$book = new Book;
			}

			$book->title 		= $goodread->title;
			$book->author 	 	= $goodread->author_name;
			$book->published 	= $goodread->book_published;
			$book->url 			= $goodread->link;
			$book->goodreads_id = $goodread->book_id;
			$book->date 		= $date;
			$book->updated_at 	= new DateTime;
			$book->updated_by 	= 1;
			$book->precedence 	= $precedence++;
			$book->save(); //to ensure there's an id?

			//save image to database
			$image = file_get_contents($goodread->book_large_image_url);
			$path_parts = pathinfo($goodread->book_large_image_url);
			$image_props = Joshreisner\Avalon\AvalonServiceProvider::saveImage(50, $image, 'cover', $path_parts['extension'], $book->id);

			if ($book->cover_id !== null) $images[] = $book->cover_id;

			$book->cover_id 		= $image_props['file_id'];
			$book->save();

		}

		if (count($images)) {
			$images = DB::table('avalon_files')->whereIn('id', $images)->get();
			Joshreisner\Avalon\AvalonServiceProvider::cleanupFiles($images);
		}

		DB::table('avalon_objects')->where('id', 10)->update(array(
			'updated_at'	=>new DateTime,
			'updated_by'	=>1,
			'count'			=>--$precedence,
		));

		return 'goodreads imported';
	}


	public function getInstagram() {

		if (!$file = file_get_contents('https://api.instagram.com/v1/users/6676862/media/recent/?access_token=' . Config::get('api.instagram.token'))) {
			trigger_error('Instagram API call did not work!');
		}

		//DB::table('photos')->truncate();

		$photos = json_decode($file);
		$images = array();

		//dd($photos);

		$precedence = 1;
		foreach ($photos->data as $pic) {
			$date = new DateTime;
			$date->setTimestamp($pic->created_time);

			if (!$photo = Photo::where('instagram_id', $pic->id)->first()) {
				$photo = new Photo;
			}
			$photo->caption			= (empty($pic->caption->text)) ? null : $pic->caption->text;
			$photo->location		= (empty($pic->location->name)) ? null : $pic->location->name;
			$photo->url 			= $pic->link;
			$photo->date 			= $date;
			$photo->updated_at		= new DateTime;
			$photo->updated_by		= 1;
			$photo->precedence		= $precedence++;
			$photo->instagram_id	= $pic->id;
			$photo->save();

			//save image to database
			$image = file_get_contents($pic->images->standard_resolution->url);
			$path_parts = pathinfo($pic->images->standard_resolution->url);
			$image_props = Joshreisner\Avalon\AvalonServiceProvider::saveImage(51, $image, 'image', $path_parts['extension'], $photo->id);

			if ($photo->image_id !== null) $images[] = $photo->image_id;

			$photo->image_id 		= $image_props['file_id'];
			$photo->save();

		}

		if (count($images)) {
			$images = DB::table('avalon_files')->whereIn('id', $images)->get();
			Joshreisner\Avalon\AvalonServiceProvider::cleanupFiles($images);
		}

		DB::table('avalon_objects')->where('id', 4)->update(array(
			'updated_at'	=>new DateTime,
			'updated_by'	=>1,
			'count'			=>--$precedence,
		));

		return 'instagram imported';
	}

	public function getInstapaper() {
		//retrieve last.fm chart infoz
		if (!$file = file_get_contents('http://www.instapaper.com/starred/rss/490270/Qy2IMb0Z4tO6Ij2QgOssRcvWg')) {
			trigger_error('Instapaper API call did not work!');
		}

		$instapaper = simplexml_load_string($file);

		dd($instapaper);
	
	}

	public function getLastFm() {

		//retrieve last.fm chart infoz
		if (!$file = file_get_contents('http://ws.audioscrobbler.com/2.0/?method=user.getLovedTracks&user=joshreisner&api_key=' . Config::get('api.lastfm') . '&period=7day&format=json')) {
			trigger_error('Last.fm API call did not work!');
		}

		DB::table('songs')->truncate();

		//clean up JSON
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
			$song->updated_at 	= new DateTime;
			$song->updated_by 	= 1;
			$song->precedence 	= $precedence++;
			$song->save();
		}

		DB::table('avalon_objects')->where('id', 2)->update(array(
			'updated_at'=>new DateTime,
			'updated_by'=>1,
			'count'=>--$precedence,
		));

		return 'lastfm imported';
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
			$article->updated_at 	= new DateTime;
			$article->updated_by 	= 1;
			$article->precedence 	= $precedence++;
			$article->save();
		}

		DB::table('avalon_objects')->where('id', 9)->update(array(
			'updated_at'	=>new DateTime,
			'updated_by'	=>1,
			'count'		=>--$precedence,
		));

		return 'readability imported';
	}

	public function getSoundCloud() {
		$soundcloud = OAuth::consumer('SoundCloud');

		if (Input::has('code')) {
		    //callback request from SoundCloud, get token
		    $soundcloud->requestAccessToken(Input::get('code'));
		    $result = json_decode($soundcloud->request('/users/219516/favorites.json'), true);
		    echo '<pre>', print_r($result);
		    exit;
		    return 'Your unique user id is: ' . $result['id'] . ' and your name is ' . $result['username'];
		} else {
			die($soundcloud->getAuthorizationUri());
			return Redirect::to($soundcloud->getAuthorizationUri());
		}
	}

	public function getTwitter() {

		$client = new \Guzzle\Service\Client('https://api.twitter.com/1.1');

		$auth = new \Guzzle\Plugin\Oauth\OauthPlugin(array(
			'consumer_key' 		=> Config::get('api.twitter.consumer_key'),
			'consumer_secret' 	=> Config::get('api.twitter.consumer_secret'),
			'token' 			=> Config::get('api.twitter.access_token'),
			'token_secret'		=> Config::get('api.twitter.access_token_secret'),
		));

		$client->addSubscriber($auth);

		$response = $client->get('statuses/user_timeline.json?username=joshreisner&exclude_replies=true')->send();

		$tweets = $response->json();
		
		DB::table('tweets')->truncate();

		$precedence = 1;

		foreach ($tweets as $tweet) {
			$tweet = (object)$tweet;

			echo '<pre>', print_r($tweet);		

			if (!empty($tweet->entities['urls'])) {
				foreach ($tweet->entities['urls'] as $url) {
					$url = (object)$url;
					$tweet->text = str_replace($url->url, '<a href="' . $url->expanded_url . '">' . $url->display_url . '</a>', $tweet->text);
				}
			}

			if (!empty($tweet->entities['user_mentions'])) {
				foreach ($tweet->entities['user_mentions'] as $user) {
					$user = (object)$user;
					$tweet->text = str_replace('@' . $user->screen_name, '<a href="https://twitter.com/' . $user->screen_name . '">@' . $user->screen_name . '</a>', $tweet->text);
				}
			}

			$date = new DateTime;
			$date->setTimestamp(strtotime($tweet->created_at));

			$status 				= new Tweet;
			$status->text 	 		= $tweet->text;
			$status->date 			= $date;
			$status->updated_at 	= new DateTime;
			$status->updated_by 	= 1;
			$status->precedence 	= $precedence++;
			$status->save();
		}

		DB::table('avalon_objects')->where('id', 6)->update(array(
			'updated_at'	=>new DateTime,
			'updated_by'	=>1,
			'count'			=>--$precedence,
		));

		return 'twitter imported';
	}

	public function getVimeo() {
		if (!$file = file_get_contents('http://vimeo.com/api/v2/joshreisner/likes.json')) {
			trigger_error('Vimeo API call did not work!');
		}

		//DB::table('videos')->truncate();

		$precedence = 1;

		$vimeos = json_decode($file);
		$images = array();
		
		//dd($vimeos);

		foreach ($vimeos as $vimeo) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($vimeo->liked_on));

			if (!$video = Video::where('vimeo_id', $vimeo->id)->first()) {
				$video 			= new Video;
			}
			$video->title 	 	= $vimeo->title;
			$video->url 		= $vimeo->url;
			$video->date 		= $date;
			$video->author 		= $vimeo->user_name;
			$video->vimeo_id	= $vimeo->id;
			$video->updated_at 	= new DateTime;
			$video->updated_by 	= 1;
			$video->precedence 	= $precedence++;
			$video->save();

			//save image to database
			$image = file_get_contents($vimeo->thumbnail_large);
			$path_parts = pathinfo($vimeo->thumbnail_large);
			$image_props = Joshreisner\Avalon\AvalonServiceProvider::saveImage(54, $image, 'image', $path_parts['extension'], $video->id);

			if ($video->image_id !== null) $images[] = $video->image_id;

			$video->image_id 		= $image_props['file_id'];
			$video->save();

		}

		if (count($images)) {
			$images = DB::table('avalon_files')->whereIn('id', $images)->get();
			Joshreisner\Avalon\AvalonServiceProvider::cleanupFiles($images);
		}

		DB::table('avalon_objects')->where('id', 7)->update(array(
			'updated_at'	=>new DateTime,
			'updated_by'	=>1,
			'count'		=>--$precedence,
		));

		return 'vimeo imported';
	}

	public function getYouTube() {

		$oauth = OAuth::consumer('Google');

	    if (Session::has('tokens.google')) {
			$youtube = json_decode($oauth->request('https://www.googleapis.com/youtube/v3/playlists'));
			
			dd($youtube);

	    } elseif (Input::has('code')) {

			Session::put('tokens.google', $oauth->requestAccessToken(Input::get('code')));
			return Redirect::to(Request::url());

	    } else {
			return Redirect::to((string)$oauth->getAuthorizationUri());
	    }

	}

	private function mapURL($latitude, $longitude) {
		return 'http://maps.googleapis.com/maps/api/staticmap?center=' . $latitude . ',' . $longitude . '&zoom=14&size=640x400&sensor=false&marker';
	}

}