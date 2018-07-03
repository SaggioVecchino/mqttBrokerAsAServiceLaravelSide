<?php

namespace App\Http\Controllers;

use App\Device_group;
use Illuminate\Http\Request;

class DeviceGroupController extends Controller
{
    //
    public function add($project_id, Request $request)
    {
        //we have to fix this validation
      /*  $this->validate($request,
            ["group_name" => "String|Required|max:255|min:5"],
            [$project_id => "Integer|min:2"]);
         */
        Device_group::create(
            [
                'group_name' => request('group_name'),
                'project_id' => $project_id
            ]
        );
        //return Device_group::all();
    }
}
