<?php

return [
	'keep_clean',
	'list' => ['image_id', 'title', 'date', 'updated_at'],
	'order_by' => ['date' => 'desc'],
	'fields' => [
		'image_id' => [
			'type' => 'image',
			'width' => 640,
		],
		'title' => 'string required',
		'description' => 'html',
		'url' => 'url',
		'date' => 'datetime',
		'updated_at',
		'updated_by',
		'deleted_at',
	],
];