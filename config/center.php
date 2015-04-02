<?php

return [
	'css' => [
		'/vendor/center/css/main.min.css',
		//'/assets/css/center.css',
	],
	'keep_clean'=>true,
	'tables' => [
		'articles' => [
			'keep_clean',
			'model' => 'Article',
			'list' => ['title', 'updated_at'],
			'fields' => [
				'title' => [
					'type' => 'string',
					'required',	
				],
				'excerpt' => 'text',
				'url' => 'url',
				'date' => 'datetime',
				'updated_at',
				'updated_by',
				'deleted_at',
			],
		],
		'books' => [
			'search' => ['title', 'author'],
			'keep_clean',
			'list' => ['title', 'date'],
			'model' => 'Book',
			'fields' => [
				'title' => [
					'type' => 'string',
					'required',	
				],
				'cover' => [
					'type' => 'image',
					'width' => 200,
				],
				'author' => 'string',
				'date' => 'date',
				'published' => 'integer',
				'rating' => 'integer',
				'goodreads_id' => [
					'type' => 'integer',
					'hidden'
				],
				'updated_at',
				'updated_by',
				'deleted_at',
			],
		],
		'checkins' => [
			'keep_clean',
			'list' => ['name', 'source', 'date', 'updated_at'],
			'model' => 'Checkin',
			'fields' => [
				'name' => 'string',
				'source' => 'string',
				'date' => 'datetime',
				'latitude' => 'string',	
				'longitude' => 'string',
				'map' => 'image',
				'facebook_id' => [
					'type' => 'string',
					'hidden',
				],
				'date' => 'datetime',
				'updated_at',
				'updated_by',
				'deleted_at',
			],
		],
	],
];