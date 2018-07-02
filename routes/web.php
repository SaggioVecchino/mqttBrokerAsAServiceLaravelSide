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

Route::post('/projects', 'ProjectController@add');
Route::post('/projects/{project_id}/device_groups', 'DeviceGroupController@add');
Route::get('/device/auth', 'DeviceController@auth');