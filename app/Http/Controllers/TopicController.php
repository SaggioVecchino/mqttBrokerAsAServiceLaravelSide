<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TopicController extends Controller
{
    function add($project_id, Request $request){
        //we have to check for the rights of adding group_names
        $this->validate($request,
            ["topic_name" => "String|Required|max:255|min:5"]); //Regex ...
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
                'topic_name' => request('topic_name'),
                'project_id' => $project_id
            ]
        );

    }
}
