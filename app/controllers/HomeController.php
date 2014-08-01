<?php

class HomeController extends BaseController {

	public function getIndex() {

		$timeline = $types = [];

		# Articles
		$articles = Article::get();
		foreach ($articles as $article) {
			$timeline[strtotime($article->date)] = [
				'type' => 'article',
				'article' => $article,
			];
		}
		$types['article'] = ['title'=>'Articles', 'count'=>count($articles)];


		# Books
		$books = Book::get();
		foreach ($books as $book) {
			$timeline[strtotime($book->date)] = [
				'type' => 'book',
				'book' => $book,
			];
		}
		$types['book'] = ['title'=>'Books', 'count'=>count($books)];


		# Check-Ins
		$checkins = Checkin::get();
		foreach ($checkins as $checkin) {
			$checkin->url = 'http://maps.google.com/?q=' . urlencode($checkin->name) . '&ll=' . $checkin->latitude . ',' . $checkin->longitude;
			$timeline[strtotime($checkin->date)] = [
				'type' => 'checkin',
				'checkin' => $checkin,
			];
		}
		$types['checkin'] = ['title'=>'Check-Ins', 'count'=>count($checkins)];


		/* Music
		$songs = Song::get();
		foreach ($songs as $song) {
			$timeline[strtotime($song->date)] = [
				'type' => 'music',
				'music' => $song,
			];
		}
		$types['music'] = ['title'=>'Music', 'count'=>count($songs)];
		*/

		# Photos
		$photos = Photo::get();
		foreach ($photos as $photo) {
			$timeline[strtotime($photo->date)] = [
				'type' => 'photo',
				'photo' => $photo,
			];
		}
		$types['photo'] = ['title'=>'Photos', 'count'=>count($photos)];


		# Projects
		$projects = Project::get();
		foreach ($projects as $project) {
			$project->domain = self::domain($project->url);
			$timeline[strtotime($project->date)] = [
				'type' => 'project',
				'project' => $project,
			];
		}
		$types['project'] = ['title'=>'Projects', 'count'=>count($projects)];


		# Statuses
		$statuses = Tweet::get();
		foreach ($statuses as $status) {
			$timeline[strtotime($status->date)] = [
				'type' => 'status',
				'status' => $status,
			];
		}
		$types['status'] = ['title'=>'Statuses', 'count'=>count($statuses)];


		# Videos
		$videos = Video::get();
		foreach ($videos as $video) {
			$timeline[strtotime($video->date)] = [
				'type' => 'video',
				'video' => $video,
			];
		}
		$types['video'] = ['title'=>'Videos', 'count'=>count($videos)];

		krsort($timeline);

		return View::make('home', [
			'articles' => $timeline,
			'types' => $types,
		]);
	}

	private static function domain($url) {
		$url = parse_url($url);
		if (substr($url['host'], 0, 4) == 'www.') return substr($url['host'], 4);
		return $url['host'];
	}

}