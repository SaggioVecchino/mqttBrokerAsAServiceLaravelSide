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


Route::patch(
    '/projects/{project_id}/device_groups/{group_name}/devices/{device_name}/disconnect',
    'DeviceController@disconnect'
);//we have to implement token middelware

Route::post(
    "/projects/{project_id}/device_groups/{group_name}/topics/authPublish",
    'Device_groups_topicController@authorizePublish'
);

Route::post(
    "/projects/{project_id}/device_groups/{group_name}/topics/authSubscribe",
    'Device_groups_topicController@authorizeSubscribe'
);

Route::post('/projects/{project_id}/topics', 'TopicController@add');

Route::post(
    '/projects/{project_id}/device_groups_topic/{group_id}',
    'Device_groups_topicController@add'
);



Route::post('/projects/{project_id}/device_groups', 'DeviceGroupController@add');

Route::post('/device/auth', 'DeviceController@auth');



Route::resources(
    [
        'projects' => 'ProjectController',
        'device_groups' => 'DeviceGroupController'
    ]
);
Route::patch('/projects/{project_id}/change_username', 'ProjectController@changeUsername');
Route::patch('/projects/{project_id}/change_password', 'ProjectController@changePassword');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get("/project_user/{project_id}", "Project_userController@show");

Route::delete("/project_user", "Project_userController@delete");

Route::post("/project_user", "Project_userController@add");
