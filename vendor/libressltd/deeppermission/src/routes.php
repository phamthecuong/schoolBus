<?php

Route::group(['middleware' => 'dprole:1'], function () {
	Route::group(['middleware' => 'web'], function () {
		Route::get("permission/setting/initial", "libressltd\deeppermission\controllers\SettingController@getInitial");
		Route::post("permission/setting/import", "libressltd\deeppermission\controllers\SettingController@postImport");
		Route::get("permission/setting/export", "libressltd\deeppermission\controllers\SettingController@getExport");
		Route::resource("permission/group", "libressltd\deeppermission\controllers\PermissionGroupController");
		Route::resource("permission/setting", "libressltd\deeppermission\controllers\SettingController");
		Route::resource("permission", "libressltd\deeppermission\controllers\PermissionController");
		Route::resource("user_role", "libressltd\deeppermission\controllers\UserRoleController");
		Route::resource("user.permission", "libressltd\deeppermission\controllers\UserPermissionController");
		Route::resource("role", "libressltd\deeppermission\controllers\RoleController");
		Route::resource("role.permission", "libressltd\deeppermission\controllers\RolePermissionController");
	});
});

