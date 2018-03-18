<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('logout', array('uses' => 'Auth\LoginController@logout'));

Route::get('/', function () {
    return redirect('/home');
});
Route::get('/home', 'HomeController@index');

/**
 * Login Facebook
 */
Route::get('redirect', 'SocialAuthController@redirect');
Route::get('callback', 'SocialAuthController@callback');

// Route::get('a', 'Service\UserParentController@listTrip');
// Auth::routes();
Route::post('/register','Auth\RegisterController@postRegister');
// Route::get('/home', 'HomeController@index');

Route::group(['middleware'=>'auth'], function() {
    Route::group(['prefix' =>'admin', 'namespace' => 'Backend\Admin'], function (){
        Route::resource('school', 'SchoolController');
        Route::get('school/{school_id}/admin/{id}/change_password', 'SchoolAdminController@getChangePassword');
        Route::post('school/{school_id}/admin/{id}/change_password', 'SchoolAdminController@postChangePassword');
        Route::resource('school.admin', 'SchoolAdminController');
        Route::resource('news', 'NewsController');
        Route::resource('banner', 'BannerController');
        Route::get('account/change_password/{id}', 'AcountController@getChangePassword');
        Route::post('change_password/{id}', 'AcountController@postChangePassword');
        Route::resource('account', 'AcountController');
    });

    Route::group(['prefix' => 'school', 'namespace' => 'Backend\School'], function (){
        
        Route::resource('trip', 'TripController');
        Route::get('trip/edit_departure/{id}','TripController@getEditDeparture');
        Route::post('trip/edit_departure/{id}','TripController@postEditDeparture');
        Route::get('trip/edit_pay_back/{id}','TripController@getEditPayBack');
        Route::post('trip/edit_pay_back/{id}','TripController@postEditPayBack');
        Route::get('student_trip/{id}','TripController@getStudentTrip');
        Route::resource('bus', 'BusController');
        Route::resource('student', 'StudentController');
        Route::get('driver/{id}/change_password','DriverController@getChangePassword');
        Route::post('driver/{id}/change_password','DriverController@postChangePassword');
        Route::resource('driver','DriverController');
        Route::resource('parent','ParentController');
        Route::resource('parent.password','ParentPassController');
        Route::resource('teacher','TeacherController');
        //Route::resource('school.admin','SchoolAdminController');
        Route::resource('class','ClassController');
        Route::resource('class_transfer', 'ClassTransferController');
        Route::post('profile/{id}/change_password', 'ProfileController@changePassword');
        Route::resource('profile','ProfileController');
        Route::get('payment/{id}/pay','PaymentController@pay');
        Route::get('payment/findstudent','PaymentController@findStudent');
        Route::resource('payment','PaymentController');
        Route::resource('news','NewsController');
        Route::resource('school_admin','SchoolAdminController');
        Route::resource('parent/student/add','ParentStudentController');
        Route::get('/departure','DepartureController@getList');
        Route::group(['prefix' => 'departure'], function() {
            Route::get('add',['as'=>'backend.departure.getAdd','uses'=>'DepartureController@getAdd']);
            Route::post('add',['as'=>'backend.departure.postAdd','uses'=>'DepartureController@postAdd']);
            Route::get('list',['as'=>'backend.departure.list','uses'=>'DepartureController@getList']);
            Route::get('delete/{id}',['as'=>'backend.departure.getDelete','uses'=>'DepartureController@getDelete']);
            Route::get('edit/{id}',['as'=>'backend.departure.getEdit','uses'=>'DepartureController@getEdit']);
            Route::post('edit/{id}',['as'=>'backend.departure.postEdit','uses'=>'DepartureController@postEdit']);
        });
        Route::resource('report', 'ReportController');
        Route::get('contact','ContactController@index');
        Route::post('contact/{id}','ContactController@read');
        Route::get('monitoring',function(){
            return view('backend.monitoring.index');
        });
        
    });

    Route::group(['prefix' => 'parent', 'namespace' => 'Backend\Parent'], function (){
        Route::resource('profile','ProfileController');
        Route::resource('payment','PaymentController');
        Route::get('trip/{id}/detail','TripController@detail');
        Route::get('trip/findbus','TripController@findBus');
        Route::resource('trip','TripController');
        Route::get('contact','ContactController@index');
        Route::post('contact','ContactController@add');
    });
});

Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax', 'middleware'=>'auth'],function(){
    Route::get('class_transfer/findclass', 'ClassTransferController@findClass');
	Route::get('student', 'StudentController@index');
	Route::get('driver', 'DriverController@index');
	Route::get('parent', 'ParentController@index');
	Route::get('teacher', 'TeacherController@index');
	Route::get('class', 'ClassController@index');
	Route::get('school', 'SchoolController@index');
	Route::get('school_admin/{school_id}', 'SchoolAdminController@index');
    Route::get('trip_departure/{id}', 'TripController@getDeparture');
    Route::get('parent_trip','TripController@getParentTrip');
    Route::get('parent_trip_detail/{id}','TripController@getParentTripDetail');
    //Route::resource('trip', 'TripController');
    Route::resource('trip', 'TripController');
    Route::get('student_trip/{id}','TripController@studentTrip');
    Route::resource('buses', 'BusController');
    Route::resource('acount', 'AcountController');
    Route::resource('admin', 'AdminController');
    Route::get('getStudent', ['as' => 'ajax.get.student'  ,'uses' => 'TripController@getStudentByClass']);
    Route::post('student_selected', ['as' => 'ajax.post.student'  ,'uses' => 'TripController@getStudent']);
    Route::get('get_pay_back',['as'=> 'ajax.get.pay_back', 'uses' => 'TripController@getPayBack']);
    Route::get('parent_payment', 'PaymentController@getParentPayment');
    Route::get('contact_school', 'ContactController@index');
    Route::get('report/{report_date}', 'ReportController@index');
    Route::get('report/{report_date}/{trip_id}', 'ReportController@getInfoTrip');
    // monitoring
    Route::resource('monitoring', 'MonitoringController');
    Route::get('getDataPopup', 'MonitoringController@getDataPopup');
    //end
});

Route::group(["prefix" => "lbmessenger", "middleware" => ["web", "auth"]], function() {
    Route::resource("conversation", "Backend\Chat\LBM_conversationController");
    Route::resource("conversation.item", "Backend\Chat\LBM_conversationItemController");

    Route::resource("contact/conversation", "Backend\Chat\ContactConversationController");

    Route::resource("ajax/conversation", "Ajax\LBM_conversationController");
    Route::resource("ajax/conversation.item", "Ajax\LBM_conversationItemController");
});

 Route::get("trip_va", "DBController@bus19");
 Route::get("trip_q", "DBController@all_function");

