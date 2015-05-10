<?php

return [
	'keep_clean',
	'list' => ['title', 'date', 'updated_at'],
	'model' => 'Project',
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