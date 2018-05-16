<?php
include 'website.php';
include 'cms.php';
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
//sai
Route::get('couter/couter={name}', 'CounterEventsController@Counter');

//đã test get page
Route::resource('eating', 'EatingController');

//đã test
Route::get('type-event', 'typeEvents@getAllEventType');

//đã test get
Route::resource('services','ServicesController');

Route::get('service/service-id={id_service}&user-id={id_user}', 'ServicesDetailsController@showDetails');
Route::post('service-postview/id={id}', 'ServicesDetailsController@postCounterView');
 

Route::resource('usersearch', 'userSearch');

//đã check
Route::resource('hotels', 'vnt_hotelsController');

Route::post('add-places', 'tourist_places_controller@AddPlace');
Route::post('add-services/{id}','tourist_places_controller@AddServices');

//đã check
Route::get('get-name-services/{id}', 'tourist_places_controller@GetNamePlace');

// Route::resource('tourist-places', 'tourist_places_Controller');
Route::resource('transport', 'transportController');

//sự kiện
//đã check
Route::resource('events', 'EventsController');

//đã check
Route::get('counter-events', 'CounterEventsController@countEvent'); 

//tham quan
Route::resource('sightseeing', 'sightseeingController');

Route::resource('entertainments', 'vnt_entertainmentsController');
Route::resource('like', 'LikeController');
Route::resource('rating', 'VisitorRatingController');

// TIM KIEM
// tìm kiếm địa điểm lân cận giới hạn 10 địa điểm


// search new
Route::get('search/placevicinity/location={latitude},{longitude}&radius={radius}','SearchController@searchPlaceVicinity');

Route::get('search/servicevicinity/location={latitude},{longtitude}&type={type}&radius={radius}','SearchController@searchServicesVicinity');

Route::get('search/searchServicesTypeKeyword/type={type}&keyword={keyword}','SearchController@searchServicesTypeKeyword');

// LOGIN-LOGOUT-REGISTER
Route::post('login-mobile','loginController@postLogin')->name('login-mobile');
Route::post('register-mobile','loginController@register')->name('register-mobile');
Route::get('logout','loginController@logout');

Route::post('edit_user_mobile/{id}','accountController@edit_user_mobile');



// web

Route::post('upload-image/{id}','ImagesController@Upload');
Route::get('get-icon/{id}', 'ImagesController@getIcon');
Route::get('get-banner/{id}', 'ImagesController@getBanner');
Route::get('get-thumb-1/{id}', 'ImagesController@getThumbnail1');
Route::get('get-thumb-2/{id}', 'ImagesController@getThumbnail2');
Route::get('get-detail-1/{id}', 'ImagesController@getImageDetail1');
Route::get('get-detail-2/{id}', 'ImagesController@getImageDetail2');
Route::get('get-only-icon-image', 'ImagesController@GetOnlyIconImage');


Route::get('rating-service/{id}','Rating_Service_Controller@rating');
Route::get('rating-view/{id_danhgia}','Rating_Service_Controller@view_rating');
Route::get('rating-view-by-user/{id_user}','Rating_Service_Controller@view_rating_by_user');

Route::post('rating-put/{id}', 'Rating_Service_Controller@putRating');
Route::post('rating-post', 'Rating_Service_Controller@postRating');
Route::get('ward', 'tourist_places_controller@GetWardList');
Route::get('province', 'tourist_places_controller@GetProvinceCity');
Route::get('ward/{id}', 'tourist_places_controller@GetWardListByID');
Route::get('district/{id}', 'tourist_places_controller@GetDisTrictListByID');

Route::get('google-maps','testGoogleMapsApi@FunctionName');
//partner
Route::get('get-services-poseted_by/month={month}&user_id={id}','Partner_Controller@getServices');
Route::get('get-places-poseted_by/month={month}&user_id={id}','Partner_Controller@getServices');
Route::get('get-task-list/{id}', 'Partner_Controller@getTaskList');

Auth::routes();
 
Route::get('/home', 'HomeController@index')->name('home');
Route::get('list-schedule/{id}', 'tripScheduleController@getListTripSchedule');
Route::post('post-schedule/user={id}', 'tripScheduleController@postTripSchedule');
Route::post('post-schedule-details/schedule={sid}', 'tripScheduleController@postTripScheduleDetail');
Route::get('list-schedule-details/{id}', 'tripScheduleController@getDetailTripSchedule');

Route::get('list-schedule-details_web/{id}', 'tripScheduleController@getDetailTripSchedule_web');

Route::get('schedule-one/{id}', 'tripScheduleController@getOneTripSchedule');
Route::get('schedule-delete/{id}', 'tripScheduleController@delete_DetailSchedule');

