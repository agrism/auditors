<?php

Route::group(
	[
		'namespace' => 'Api', 'middleware' => ['forClient'], 'prefix' => 'api',
		'as' => 'api.',
	], function () {

	// Route::resource('partners', 'PartnerController');

}
);
