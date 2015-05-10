<?php

return [
	'keep_clean',
	'list' => ['name', 'source', 'date', 'updated_at'],
	'order_by' => ['date' => 'desc'],
	'fields' => [
		'name' => 'string',
		'source' => 'string',
		'date' => 'datetime',
		'latitude' => 'string',	
		'longitude' => 'string',
		'map_id' => 'image',
		'facebook_id' => [
			'type' => 'string',
			'hidden',
		],
		'updated_at',
		'updated_by',
		'deleted_at',
	],
];