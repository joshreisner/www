<?php

class ImportController extends BaseController {

	public function getVimeo() {
		
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

		echo '<pre>', print_r($tweets);
	}

	public function getReadability() {
		//phpinfo();
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

		echo '<pre>', print_r($photos);
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

		echo '<pre>', print_r($tracks);

	}
}