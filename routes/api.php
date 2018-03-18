<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => 'auth:api'], function () {
	Route::get('user/school', 'Service\UserController@getSchoolInfo');
	Route::resource('user', 'Service\UserController');
	//parent
	Route::post('parent/list-trip', 'Service\UserParentController@listTrip');
	Route::post('parent/logs', 'Service\UserParentController@logs');
	Route::post('parent/payment', 'Service\UserParentController@payment');
	Route::post('parent/add-student-code', 'Service\UserParentController@addStudentCode');
	Route::resource('parent/distance', 'Service\ParentDistanceController');

	//driver
	Route::post('driver/list-trip', 'Service\UserDriverController@listTrip');
	Route::post('driver/active-trip', 'Service\UserDriverController@activeTrip');

	//trip
	Route::post('trip/{id}/note', 'Service\TripController@postNote');
	Route::post('trip/detail', 'Service\TripController@detail');
	Route::post('trip/detail-for-parent', 'Service\TripController@detailForParent');
	// Route::post('trip/send-location', 'Service\TripController@sendLocation');
	Route::post('trip/update-location', 'Service\TripController@updateLocation');
	Route::get('trip/get-location/{id}', 'Service\TripController@getLocation');
	// Route::post('trip/finish-departure', 'Service\TripController@finishdDeparture');
	// Route::post('trip/forgot-student', 'Service\TripController@forgotStudent');
	Route::post('trip/{id}/finish', 'Service\TripController@finish');
	// Route::resource('trip.departure', 'Service\TripDepartureController');

	//student
	Route::resource('student.trip', 'Service\StudentTripController');
	Route::post('student/trip/{trip_id}/all', 'Service\StudentTripController@changeAll');

	Route::resource('device', 'Service\DeviceController');

	Route::post('conversation', 'Service\ConversationController@store');
	Route::post('conversation/{conversation_id}/item', 'Service\ConversationController@addMessage');
	Route::get('conversation/{conversation_id}/item', 'Service\ConversationController@getMessage');
	Route::post('conversation/{conversation_id}/new-item', 'Service\ConversationController@getNewMessage');
	Route::get('conversation', 'Service\ConversationController@index');

	Route::get('template-chat', 'Service\HomeController@getTemplateChat');
	Route::resource("contact-message", "Service\Contact_message");

	Route::get('news', 'Service\HomeController@getNews');
	Route::resource('departure', 'Service\DepartureController');
});

//Auth
Route::post('user/forgotpassword', 'Service\UserController@postEmail');
Route::post('user/login', 'Service\UserController@postLogin');
Route::post('user/register', 'Service\UserController@postRegister');
Route::post('user/login-facebook', 'Service\UserController@postRegisterFacebook');

Route::get('banner', 'Service\HomeController@getBanner');
