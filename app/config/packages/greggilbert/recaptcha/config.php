<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| API Keys
	|--------------------------------------------------------------------------
	|
	| Set the public and private API keys as provided by reCAPTCHA.
	|
	*/
	'public_key'	=> getenv('RECAPTCHA_PUBLIC_KEY'),
	'private_key'	=> getenv('RECAPTCHA_PRIVATE_KEY'),
	
	/*
	|--------------------------------------------------------------------------
	| Template
	|--------------------------------------------------------------------------
	|
	| Set a template to use if you don't want to use the standard one.
	|
	*/
	'template'		=> '',
	
);