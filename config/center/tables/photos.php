<?php

return [
	'keep_clean',
	'list' => ['image_id', 'location', 'date', 'updated_at'],
	'order_by' => ['date' => 'desc'],
	'fields' => [
		'image_id' => [
			'type' => 'image',
			'width' => 640,
			'height' => 640,
		],
		'location' => 'string',
		'url' => 'url',
		'date' => 'datetime',
		'instagram_id' => [
			'type' => 'string',
			'hidden',
		],
		'caption' => 'string',
		'updated_at',
		'updated_by',
		'deleted_at',
	],
];