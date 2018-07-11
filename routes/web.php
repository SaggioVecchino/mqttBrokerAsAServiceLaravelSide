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

Route::get('/', function () {
    return view('welcome');
});


//A revérifier les routes



Route::prefix('/projects/{project_id}/device_groups/{group_name}')->group(function () {

    Route::patch(
        '/devices/{device_name}/disconnect',
        'DeviceController@disconnect');//we have to implement token middelware

    Route::post(
        "/topics/authPublish",
        'Device_groups_topicController@authorizePublish');

    Route::post(
        "/topics/authSubscribe",
        'Device_groups_topicController@authorizeSubscribe');

});

Route::post('/device/auth', 'DeviceController@auth');




Route::resources([
    "topics" => "TopicController",
    "devices" => "DeviceController",
    "project_users" => "Project_userController",
    "device_groups_topics" => "Device_groups_topicController"
]);


Route::prefix('/device_groups_topics/{device_groups_topic}')
    ->group(function () {
        Route::patch("changeType",
            "Device_groups_topicController@changeType");

        Route::patch("changeVerdict",
            "Device_groups_topicController@changeVerdict");
    });


Route::post('/projects/{project_id}/device_groups_topic/{group_id}',
'Device_groups_topicController@add');

Route::post('/projects', 'ProjectController@add');



Route::post('/projects/{project_id}/device_groups', 'DeviceGroupController@add');




Route::patch('/projects/{project_id}/auth', 'ProjectController@edit');


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');



Route::delete("projects/{project_id}","ProjectController@delete");



Route::get("/projects","ProjectController@show");



Route::get("/project_user/{project_id}/contributors","Project_userController@showContributors");
Route::delete("/project_user/destroyContributor","Project_userController@destroyContributor");
