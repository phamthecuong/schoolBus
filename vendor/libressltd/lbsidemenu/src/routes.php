<?php

Route::group(['middleware' => 'web', 'prefix' => 'lbsm'], function () {
	Route::resource("item", "libressltd\lbsidemenu\controllers\LBSM_itemController");
});
