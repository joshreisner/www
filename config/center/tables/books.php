<?php

return [
	'search' => ['title', 'author'],
	'keep_clean',
	'list' => ['cover_id', 'title', 'date'],
	'model' => 'Book',
	'fields' => [
		'title' => [
			'type' => 'string',
			'required',	
		],
		'cover_id' => [
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
];