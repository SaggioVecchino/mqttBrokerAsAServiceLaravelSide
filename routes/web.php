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
        'DeviceController@disconnect'
    );//we have to implement token middelware

    Route::post(
        "/topics/authPublish",
        'Device_groups_topicController@authorizePublish'
    );

    Route::post(
        "/topics/authSubscribe",
        'Device_groups_topicController@authorizeSubscribe'
    );

});

Route::post('/device/auth', 'DeviceController@auth');


Route::resources([
    "topics" => "TopicController",
    "devices" => "DeviceController",
    "project_users" => "Project_userController",
    "device_groups_topics" => "Device_groups_topicController",
    'projects' => 'ProjectController',
    'device_groups' => 'DeviceGroupController'
]);


Route::prefix('/device_groups_topics/{device_groups_topic}')
    ->group(function () {
        Route::patch(
            "change_type",
            "Device_groups_topicController@changeType"
        );

        Route::patch(
            "change_verdict",
            "Device_groups_topicController@changeVerdict"
        );
    });

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('/projects/{project_id}')
    ->group(function () {
        Route::patch('change_project_name', 'ProjectController@changeProjectName');
        Route::patch('change_password', 'ProjectController@changePassword');

        Route::resource('contributors', 'ContributorController')->only(['index', 'destroy']);
    });

Route::prefix('/users/{user_id}')
    ->group(function () {
        Route::resource('contributions', 'ContributionController')->only(['index', 'destroy']);
    });