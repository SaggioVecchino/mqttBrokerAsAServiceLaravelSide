<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Device_groups_topic;
use App\Device_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class Device_groups_topicController extends Controller
{
    function add($project_id, $group_id, Request $request)
    {
        $this->validate(
            $request,
            [
                "allow" => "required|Boolean",
                "type" => "required|in:publication,subscribtion",
                "topic_id" => "required|Integer|min:1"
            ]
        );
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

        // we have to deal with the case in which topic don't have the same project_id here

        Device_groups_topic::create(
            [
                "group_id" => $group_id,
                "project_id" => $project_id,
                "topic_id" => request("topic_id"),
                "allow" => request("allow"),
                "type" => request("type")
            ]
        );
    }

    function authorizePublish($project_id, $group_name, Request $request)
    {
        try {
            $group_id = Device_group::where([
                ['group_name', '=', $group_name],
                ['project_id', '=', $project_id]
            ])->firstOrFail()->id;
        } catch (ModelNotFoundException $e) {
            //project-group problem
            $flag = [
                'flag' => false,
                'message' =>
                    'Project: ' . 'project_id' . ' doesn\'t exist or/and Group '
                    . 'group_name' . ' doesn\'t in the project'
            ];
            return $flag;
        }

        $disallowed = DB::table('device_groups_topics')->where([
            ['device_groups_topics.project_id', '=', $project_id],
            ['device_groups_topics.group_id', '=', $group_id],
            ['device_groups_topics.allow', '=', false],
            ['device_groups_topics.type', '=', 'publication']
        ])->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get()
            ->first(function ($prohibition) {
                return preg_match($prohibition->topic_name, request("topic"));
            });

        if ($disallowed) {
            $flag = [
                "flag" => false,
                "message" => "disallowed"
            ];
            return $flag;
        }

        $allowed = DB::table('device_groups_topics')->where([
            ['device_groups_topics.project_id', '=', $project_id],
            ['device_groups_topics.group_id', '=', $group_id],
            ['device_groups_topics.allow', '=', true],
            ['device_groups_topics.type', '=', 'publication']
        ])->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get()
            ->first(function ($permission) {
                return preg_match($permission->topic_name, request("topic"));
            });

        if ($allowed) {
            $flag = [
                "flag" => true,
                "message" => "allowed"
            ];
            return $flag;
        }

        $flag = [
            "flag" => false,
            "message" => "disallowed"
        ];
        return $flag;

    }

    function authorizeSubscribe($project_id, $group_name, $topic)
    {
        $flag = [
            "flag" => false,
            "message" => "unable to subscribe !!!!"
        ];
        return $flag;
    }

}
