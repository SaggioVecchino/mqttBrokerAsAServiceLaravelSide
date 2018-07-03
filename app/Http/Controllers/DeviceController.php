<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller
{
    function add(Request $request)
    {
        $validatedData = $this->validate($request, [
            'device_name' => 'required|string|max:255|min:5',
            'project_id' => 'required|integer',
            'group_id' => 'required|integer'
        ]);

        Project::create(
            [
                'device_name' => request('device_name'),
                'project_id' => request('project_id'),
                'group_id' => request('group_id')
            ]
        );
    }

    function auth(Request $request)
    {
        $validatedData = $this->validate($request, [
            'device_name' => 'required|string|max:255|min:5',
            'project_id' => 'required|integer',
            'group_id' => 'required|integer',
            'password_md5' => 'required|string|size:32'
            // The password_md5 the user pass must be md5(password of the project)
        ]);

        $bcmd5 = DB::select('password')
            ->from('projects')
            ->where('id', request('project_id'))->first();
        if ($bcmd5->password == Hash::make(request('password_md5'))) {
            $device = DB::select('id')
                ->from('devices')
                ->where([
                    ['device_name', '=', request('device_name')],
                    ['group_id', '=', request('group_id')],
                    ['project_id', '=', request('project_id')]
                ])->first();
            if ($device) {
                $device->connected = true;
                $device->save();
            } else {
                //device don't exist we have to create it
                $this::add($request);
            }
            //return a true flag
        }
        else{
            //Auth error

            //return a false flag
        }
    }
}
