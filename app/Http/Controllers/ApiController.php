<?php namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use LeftRight\Center\Models\Article;
use LeftRight\Center\Models\Book;
use LeftRight\Center\Models\Checkin;
use LeftRight\Center\Models\Photo;
use LeftRight\Center\Models\Tweet;
use LeftRight\Center\Models\Video;
use LeftRight\Center\Libraries\Slug;
use LeftRight\Center\CenterServiceProvider;
use DateTime;
use DB;
use Redirect;
use Request;
use Session;

class ApiController extends Controller {	
	public function facebook() {

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
				$checkin->save();
				
				//save image to database
				$image_props = CenterServiceProvider::saveImage('checkins', 'image_id', self::mapURL($checkin->latitude, $checkin->longitude), $checkin->id);

				if ($checkin->map_id !== null) $images[] = $checkin->map_id;

				$checkin->map_id 		= $image_props['file_id'];
				$checkin->save();

			}

			if (count($images)) {
				$images = DB::table('avalon_files')->whereIn('id', $images)->get();
				CenterServiceProvider::cleanupFiles($images);
			}

			return 'Facebook imported';

	    } elseif (Input::has('code')) {

			Session::put('tokens.facebook', $oauth->requestAccessToken(Input::get('code')));
			return Redirect::to(Request::url());

	    } else {
			return Redirect::to((string)$oauth->getAuthorizationUri());
	    }
	}

	public function foursquare() {

		if (!$file = file_get_contents('https://feeds.foursquare.com/history/' . env('FOURSQUARE') . '.kml')) {
			trigger_error('Foursquare API call did not work!');
		}

		$checkins = simplexml_load_string($file);

		//dd($checkins);

		//DB::table('checkins')->truncate();

		foreach ($checkins->Folder->Placemark as $placemark) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($placemark->published));

			list($longitude, $latitude) = explode(',', $placemark->Point->coordinates);

			$checkin = Checkin::firstOrNew([
				'facebook_id' => null,
				'latitude'    => $latitude,
				'longitude'   => $longitude,
			]);
			
			$checkin->name 	 		= $placemark->name;
			$checkin->date 			= $date;
			$checkin->latitude 		= $latitude;
			$checkin->longitude 	= $longitude;
			$checkin->source 		= 'Foursquare';
			$checkin->updated_at 	= new DateTime;
			$checkin->updated_by 	= 1;
			$checkin->save();
			
			//save image to database
			$image_props = CenterServiceProvider::saveImage('checkins', 'map_id', self::mapURL($checkin->latitude, $checkin->longitude), $checkin->id);

			if ($checkin->map_id !== null) $images[] = $checkin->map_id;

			$checkin->map_id 		= $image_props['file_id'];
			$checkin->save();

		}

		return 'foursquare imported';
	}

	public function goodreads() {
		if (!$file = file_get_contents('https://www.goodreads.com/review/list_rss/9112494?key=a9d74013bd7f71963fa627b54e49f3a3856b1108&shelf=%23READ%23')) {
			trigger_error('Goodreads API call did not work!');
		}

		$goodreads = simplexml_load_string($file);

		//dd($goodreads);

		DB::table('books')->truncate();
		//DB::table('avalon_files')->truncate();

		$precedence = 1;
		$images = array();

		foreach ($goodreads->channel->item as $goodread) {
			
			$date = new DateTime;
			$date->setTimestamp(strtotime($goodread->user_read_at));

			$book = Book::firstOrNew(['goodreads_id'=>$goodread->book_id]);

			$book->title 		= $goodread->title;
			$book->author 	 	= $goodread->author_name;
			$book->published 	= $goodread->book_published;
			//$book->url 			= $goodread->link;
			//$book->slug			= Slug::make($goodread->title);
			$book->goodreads_id = $goodread->book_id;
			$book->rating		= $goodread->user_rating;
			$book->date 		= $date;
			$book->updated_at 	= new DateTime;
			$book->updated_by 	= 1;
			//$book->precedence 	= $precedence++;
			$book->save(); //to ensure there's an id?

			//save image to database
			$image_props = CenterServiceProvider::saveImage('books', 'cover_id', $goodread->book_large_image_url, $book->id);

			if ($book->cover_id !== null) $images[] = $book->cover_id;

			$book->cover_id 		= $image_props['file_id'];
			$book->save();

		}

		if (count($images)) {
			$images = DB::table(config('center.db.files'))->whereIn('id', $images)->get();
			CenterServiceProvider::cleanupFiles($images);
		}

		return 'goodreads imported';
	}


	public function instagram() {

		if (!$file = file_get_contents('https://api.instagram.com/v1/users/6676862/media/recent/?access_token=' . env('INSTAGRAM'))) {
			trigger_error('Instagram API call did not work!');
		}

		//DB::table('photos')->truncate();

		$photos = json_decode($file);

		//dd($photos);

		foreach ($photos->data as $pic) {
			$date = new DateTime;
			$date->setTimestamp($pic->created_time);

			$photo = Photo::firstOrNew(['instagram_id'=>$pic->id]);
			$photo->caption			= (empty($pic->caption->text)) ? null : $pic->caption->text;
			$photo->location		= (empty($pic->location->name)) ? null : $pic->location->name;
			$photo->url 			= $pic->link;
			$photo->date 			= $date;
			$photo->updated_at		= new DateTime;
			$photo->updated_by		= 1;
			$photo->instagram_id	= $pic->id;
			$photo->save();

			//save image to database
			$image_props = CenterServiceProvider::saveImage('photos', 'image_id', $pic->images->standard_resolution->url, $photo->id);

			if ($photo->image_id !== null) $images[] = $photo->image_id;

			$photo->image_id 		= $image_props['file_id'];
			$photo->save();
		}

		return 'instagram imported';
	}

	public function instapaper() {
		//retrieve last.fm chart infoz
		if (!$file = file_get_contents('http://www.instapaper.com/starred/rss/490270/Qy2IMb0Z4tO6Ij2QgOssRcvWg')) {
			trigger_error('Instapaper API call did not work!');
		}

		$instapaper = simplexml_load_string($file);

		dd($instapaper);
	
	}

	public function readability() {
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
			$article->save();
		}

		return 'readability imported';
	}

	public function twitter() {

		$client = new Client(['base_url' => 'https://api.twitter.com/1.1/']);
		
		$oauth = new Oauth1([
		    'consumer_key'    => env('TWITTER_CONSUMER_KEY'),
		    'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
		    'token'           => env('TWITTER_ACCESS_TOKEN'),
		    'token_secret'    => env('TWITTER_ACCESS_TOKEN_SECRET'),
		]);
		
		$client->getEmitter()->attach($oauth);

		$response = $client->get('statuses/user_timeline.json?username=joshreisner&exclude_replies=true', ['auth' => 'oauth']);

		$tweets = $response->json();
		
		DB::table('tweets')->truncate();

		foreach ($tweets as $tweet) {
			$tweet = (object)$tweet;

			//echo '<pre>', print_r($tweet);		

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
			$status->save();
		}

		return 'twitter imported';
	}

	public function vimeo() {
		if (!$file = file_get_contents('http://vimeo.com/api/v2/joshreisner/likes.json')) {
			trigger_error('Vimeo API call did not work!');
		}

		DB::table('videos')->truncate();

		$precedence = 1;

		$vimeos = json_decode($file);
		$images = array();
		
		//dd($vimeos);

		foreach ($vimeos as $vimeo) {

			$date = new DateTime;
			$date->setTimestamp(strtotime($vimeo->liked_on));

			$video = Video::firstOrNew(['vimeo_id'=>$vimeo->id]);
			$video->title 	 	= $vimeo->title;
			$video->url 		= $vimeo->url;
			$video->date 		= $date;
			$video->author 		= $vimeo->user_name;
			$video->vimeo_id	= $vimeo->id;
			$video->updated_at 	= new DateTime;
			$video->updated_by 	= 1;
			$video->source 	 	= 'Vimeo';
			$video->save();

			//save image to database
			$image_props = CenterServiceProvider::saveImage('videos', 'image_id', $vimeo->thumbnail_large, $video->id);

			if ($video->image_id !== null) $images[] = $video->image_id;

			$video->image_id 		= $image_props['file_id'];
			$video->save();
		}

		return 'Vimeo imported';
	}

	private function mapURL($latitude, $longitude) {
		return 'http://maps.googleapis.com/maps/api/staticmap?center=' . $latitude . ',' . $longitude . '&zoom=14&size=640x400&sensor=false&marker';
	}
}