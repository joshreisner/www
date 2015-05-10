<?php

return [
	'keep_clean',
	'list' => ['image_id', 'title', 'date', 'updated_at'],
	'order_by' => ['date' => 'desc'],
	'model' => 'Video',
	'fields' => [
		'image_id' => [
			'type' => 'image',
			'width' => 640,
		],
		'title' => 'string required',
		'url' => 'url',
		'date' => 'datetime',
		'author' => 'string',
		'source' => 'string',
		'vimeo_id' => 'string',
		'updated_at',
		'updated_by',
		'deleted_at',
	],
];