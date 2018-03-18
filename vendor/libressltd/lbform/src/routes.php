<?php

Route::group(["middleware" => ["web"]], function() {
	Route::get('setLang/{lang}', 'libressltd\lbform\controllers\LBFormController@getSetLang');
});

Route::group(["middleware" => ["web", "auth"], 'prefix' => 'lbform', 'namespace' => 'libressltd\lbform\controllers'], function(){
	Route::get('logout', "LBFormController@logout");
});