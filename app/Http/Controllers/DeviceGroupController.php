<?php

namespace App\Http\Controllers;

use App\Device_group;
use Illuminate\Http\Request;

class DeviceGroupController extends Controller
{
    //
    public function add(Request $request){
        $this->validate($request,["group_name"=>"String|Required"],[$project_id]="Integer");
        return Device_group::create(["group_name"=>$request->group_name,'project_id'=>$project_id]);

    }
}
