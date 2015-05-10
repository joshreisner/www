<?php

return [
	'keep_clean',
	'list' => ['text', 'date', 'updated_at'],
	'order_by' => ['date' => 'desc'],
	'fields' => [
		'text' => 'text required',
		'date' => 'datetime',
		'updated_at',
		'updated_by',
		'deleted_at',
	],
];