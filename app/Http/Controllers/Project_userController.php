<?php

namespace App\Http\Controllers;

use App\Project_user;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class Project_userController extends Controller
{
    public function add($project_id, Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|Integer|min:1',
        ]);
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

        Project_user::create(
            [
                'user_id' => request('user_id'),
                'project_id' => $project_id
            ]
        );
        return Project_user::all();
    }

    public function show($project_id)
    {
        $users = DB::table("project_users")->where([
            ["project_users.project_id", '=', $project_id]
        ])->join("users", "users.id", "=", "project_users.user_id")
            ->select("users.id", "users.email", "users.name")
            ->get();
        return $users;
    }
}
