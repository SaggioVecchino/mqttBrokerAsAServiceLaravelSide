<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Device;
use App\Project;
use App\Device_group;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeviceController extends Controller
{
    function add(Request $request)
    {
        $validatedData = $this->validate($request, [
            'device_name' => 'required|string|max:255|min:5',
            'project_id' => 'required|integer',
            'group_id' => 'required|integer'
        ]);

        Device::create(
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
            'device_name' => 'required|string|min:5|max:255',
            'project_id' => 'required|integer',
            'group_name' => 'required|string|min:5|max:255',
            'password' => 'required|string'
        ]);

            //We have to handle the invalid input by sending
            //a flag false with a message 'invalid input'

        try {
            $passhashed = (Project::findOrFail(request('project_id')))->password;
        } catch (ModelNotFoundException $e) {
            $flag = [
                'flag' => false,
                'message' => 'Authentication error:\n' .
                    'project_id: ' . request('project_id') . ' doesn\'t exist.'
            ];

            return $flag;
        }

        if (Hash::check(request('password'), $passhashed)) {

            try {
                $group_id = (Device_group::where([
                    ['group_name', '=', request('group_name')],
                    ['project_id', '=', request('project_id')]
                ])->firstOrFail())->id;
            } catch (ModelNotFoundException $e) {
                //the group doesn't exist
                $flag = [
                    'flag' => false,
                    'message' =>
                        'The group: ' . request('group_name') . ' isn\'t present in the project 
                    with project_id: ' . request('project_id')
                ];

                return $flag;
            }

            try {
                $device = Device::where([
                    ['device_name', '=', request('device_name')],
                    ['group_id', '=', $group_id],
                    ['project_id', '=', request('project_id')]
                ])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                //device don't exist we have to create it
                Device::create(
                    [
                        'device_name' => request('device_name'),
                        'project_id' => request('project_id'),
                        'group_id' => $group_id
                    ]
                );
                $flag = [
                    'flag' => true,
                    'message' => 'Device added to database successfully'
                ];

                return $flag;
            }
            if (!$device->connected) {
                $device->connected = true;
                $device->save();

                $flag = [
                    'flag' => true,
                    'message' => 'Device already in database.\nState of connection is
                now set to connected.'
                ];

                return $flag;
            } else {
                $flag = [
                    'flag' => false,
                    'message' => 'Device name already in use.'
                ];
    
                return $flag;

            }
        } else {
            //Auth error

            $flag = [
                'flag' => false,
                'message' => 'Authentication error.\n' .
                    'The password provided doesn\'t match the password of the project_id: ' .
                    request('project_id')
            ];

            return $flag;
        }
    }


    function disconnect($project_id, $group_name, $device_name)
    {
        //we have to check that only the borker can use this methode
        try {
            $group_id = (Device_group::where([
                ["group_name", "=", $group_name],
                ["project_id", "=", $project_id]
            ])->firstOrFail())->id;
        } catch (ModelNotFoundException $e) {
            $flag = [
                "flag" => false,
                "message" => "Disconnection failed, the group" . $group_name . " doesn\'t exist in the project: " . $project_id
            ];
            return $flag;
        }

        try {
            $device = (Device::where([
                ["group_id", "=", $group_id],
                ["project_id", "=", $project_id],
                ["device_name", "=", $device_name],
            ])->firstOrFail());
            $device->connected = false;
            $device->save();
            $flag = [
                "flag" => true,
                "message" => "Successful deconnection of the device" . $device_name
            ];
            return $flag;
        } catch (ModelNotFoundException $e) {
            $flag = [
                "flag" => false,
                "message" => "Disconnection failed, the device" . $device_name . " doesn\'t exist"
            ];
            return $flag;
        }
    }
}
