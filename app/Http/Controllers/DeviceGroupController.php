<?php

namespace App\Http\Controllers;

use App\Device_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DeviceGroupController extends Controller
{
    public function add($project_id, Request $request)
    {
        //we have to check for the rights of adding group_names
        $this->validate($request,
            ["group_name" => "String|Required|max:255|min:5"]);
        //https://martinbean.co.uk/blog/2017/09/28/validating-more-than-request-data-in-laravel-form-requests/
        $attributes = [
            'project_id' => $project_id,
        ];
        $rules = [
            'project_id' => 'required|Integer|min:1',
        ];
        $validator = Validator::make($attributes, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        Device_group::create(
            [
                'group_name' => request('group_name'),
                 'project_id' => $project_id
            ]
        );
        //return Device_group::all();
    }
}
