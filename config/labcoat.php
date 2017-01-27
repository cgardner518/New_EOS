<?php
//This is where configuration details will be read from.

return [
	'sitename' => 'Labcoat',
	'orgname' => 'Naval Research Laboratory',
	'logo' => 'build/images/logo.png',
	'debug' => env('APP_DEBUG', false),
	'securityColor' => env('APP_SECURE_COLOR', 'green'),
	'securityMessage' => env('APP_SECURE_MESSAGE', 'Unclassified'),
];
