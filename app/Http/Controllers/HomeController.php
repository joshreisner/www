<?php namespace App\Http\Controllers;

use LeftRight\Center\Models\Article;
use LeftRight\Center\Models\Book;
use LeftRight\Center\Models\Checkin;
use LeftRight\Center\Models\Photo;
use LeftRight\Center\Models\Project;
use LeftRight\Center\Models\Tweet;
use LeftRight\Center\Models\Video;

class HomeController extends Controller {

	public function index() {

		$timeline = [];

		# Articles
		$articles = Article::get();
		foreach ($articles as $article) {
			$article->domain = self::domain($article->url);
			$timeline[strtotime($article->date)] = [
				'type' => 'article',
				'article' => $article,
			];
		}


		# Books
		$books = Book::get();
		foreach ($books as $book) {
			$timeline[strtotime($book->date)] = [
				'type' => 'book',
				'book' => $book,
			];
		}


		# Check-Ins
		$checkins = Checkin::get();
		foreach ($checkins as $checkin) {
			$checkin->url = 'http://maps.google.com/?q=' . urlencode($checkin->name) . '&ll=' . $checkin->latitude . ',' . $checkin->longitude;
			$timeline[strtotime($checkin->date)] = [
				'type' => 'checkin',
				'checkin' => $checkin,
			];
		}


		# Photos
		$photos = Photo::get();
		foreach ($photos as $photo) {
			$timeline[strtotime($photo->date)] = [
				'type' => 'photo',
				'photo' => $photo,
			];
		}


		# Projects
		$projects = Project::get();
		foreach ($projects as $project) {
			$project->domain = self::domain($project->url);
			$timeline[strtotime($project->date)] = [
				'type' => 'project',
				'project' => $project,
			];
		}


		# Statuses
		$statuses = Tweet::get();
		foreach ($statuses as $status) {
			$timeline[strtotime($status->date)] = [
				'type' => 'status',
				'status' => $status,
			];
		}


		# Videos
		$videos = Video::get();
		foreach ($videos as $video) {
			$timeline[strtotime($video->date)] = [
				'type' => 'video',
				'video' => $video,
			];
		}

		krsort($timeline);

		return view('home', [
			'articles' => $timeline,
		]);
	}

	private static function domain($url) {
		$url = parse_url($url);
		if (substr($url['host'], 0, 4) == 'www.') return substr($url['host'], 4);
		return $url['host'];
	}

}
