<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List all plugins
	*/
	Route::get('admin/extend/plugins', function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		return View::create('extend/plugins/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});
	
});

Route::collection(array('before' => 'auth'), function() {
	Route::get('admin/bookz', function($page = 1) {
		require APP . 'bookz/bookz' . EXT;
	});
	
	Route::post('admin/bookz', function($page = 1) {
		require APP . 'bookz/bookz' . EXT;
	});
});


