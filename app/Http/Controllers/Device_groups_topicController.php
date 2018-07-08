<?php

namespace App\Http\Controllers;
use App\Device_groups_topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Device_groups_topicController extends Controller
{
    function add($project_id,$group_id,Request $request){
        $this->validate($request,
            [
                "level" => "Integer|Required|min:0",
                "allow" => "Boolean",
                "type" => "in:publication,subscribtion",
                "topic_id" => "required|Integer|min:1"
            ]);

        $attributes = [
            "project_id" => $project_id,
            "group_id" => $group_id
        ];
        $rules = [
            "project_id" => "required|Integer|min:1",
            "group_id" => "required|Integer|min:1"
        ];
        $validator = Validator::make($attributes, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        Device_groups_topic::create(
            [
                "group_id" => $group_id,
                "project_id" => $project_id,
                "topic_id" => request("topic_id"),
                "allow" => request("allow"),
                "level" => request("level"),
                "type" => request("type")
            ]
        );
    }
    function authorizePublish($project_id, $group_name,$topic){
            $flag = [
                "flag" => false,
                "message" => "unable to publish !!!!"
            ];
        return $flag;
    }
    function authorizeSubscribe($project_id, $group_name,$topic){
        $flag = [
            "flag" => false,
            "message" => "unable to subscribe !!!!"
        ];
        return $flag;
    }

}
