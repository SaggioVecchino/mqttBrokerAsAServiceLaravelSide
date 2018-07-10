<?php

namespace App\Http\Controllers;

use App\Project_user;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Project_userController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|Integer|min:1',
            'project_id' => 'required|Integer|min:1'
        ]);

        Project_user::create(
            [
                'user_id' => request('user_id'),
                'project_id' => request('project_id')
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

    public function delete(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required|integer|min:1',
            'user_id' => 'required|integer|min:1'
        ]);
        $project_user_id = null;
        try {
            $contributor = Project_user::where([
                ['project_id', '=', request('project_id')],
                ['user_id', '=', request('user_id')]
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->withErrors(['msg', 'No such contributor to such project']);
        }
        $contributor->delete();
        return Project_user::all();
    }
}
