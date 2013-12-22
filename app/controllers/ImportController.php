<?php

class ImportController extends BaseController {

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

	public function getFoursquare() {

		if (!$file = file_get_contents('https://feeds.foursquare.com/history/' . Config::get('api.foursquare') . '.kml')) {
			trigger_error('Foursquare API call did not work!');
		}

		DB::table('checkins')->truncate();

		$precedence = 1;

		$checkins = simplexml_load_string($file);

		foreach ($checkins->Folder->Placemark as $placemark) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($placemark->published));
			
			list($latitude, $longitude) = explode(',', $placemark->Point->coordinates);

			$checkin 				= new Checkin;
			$checkin->name 	 		= $placemark->name;
			$checkin->date 			= $date;
			$checkin->latitude 		= $latitude;
			$checkin->longitude 	= $longitude;
			$checkin->updated 		= new DateTime;
			$checkin->updater 		= 1;
			$checkin->active 		= 1;
			$checkin->precedence 	= $precedence++;
			$checkin->save();
		}

		DB::table('avalon_objects')->where('id', 8)->update(array(
			'updated'	=>new DateTime,
			'updater'	=>1,
			'count'		=>--$precedence,
		));

		return @Kint::dump($checkins);
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