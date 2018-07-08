<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TopicController extends Controller
{
    function add($project_id, Request $request)
    {
        //we have to check for the rights of adding group_names
        $this->validate(
            $request,
            [
                'topic_name' => [
                    'required',
                    'string',
                    'min:1',
                    'max:255',
                    'regex:/^([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?$/'
                ]
            ]
        );
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
        Topic::create(
            [
                'topic_name' => $this::permissionToRegEx(request('topic_name')),
                'project_id' => $project_id
            ]
        );
    }

    private static function permissionToRegEx($topic)
    {
        $fields = explode('/', $topic);
        $regEx = '';
        foreach ($fields as $field) {
            if ($field == '+') {
                $regEx .= '[\\w ]+\\/';
            } elseif ($field == '#') {
                $regEx .= '([\\w ]+|\\+)(\\/([\w ]+|\\+))*(\\/\\#)?';
            } else {
                $regEx .= $field . '\\/';
            }
        }
        $len = strlen($regEx);
        if ($regEx {
            $len - 1} == '/')
            $regEx = substr($regEx, 0, $len - 2);
        $regEx = '/^' . $regEx . '$/';
        return $regEx;
    }

}
