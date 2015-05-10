<?php

return [
	'keep_clean',
	'list' => ['title', 'updated_at'],
	'fields' => [
		'title' => 'string required',	
		'excerpt' => 'text',
		'url' => 'url',
		'date' => 'datetime',
		'updated_at',
		'updated_by',
		'deleted_at',
	],
];