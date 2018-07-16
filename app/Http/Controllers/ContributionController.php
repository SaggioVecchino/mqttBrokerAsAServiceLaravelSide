<?php

namespace App\Http\Controllers;

use App\Project_user;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    /**
     * Display a listing of the contributions(projects) for a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        return DB::table("project_users")->where([
            ["project_users.user_id", '=', $user_id]
        ])->join("projects", "projects.id", "=", "project_users.project_id")
            ->select("projects.*")
            ->get();
    }

    /**
     * Remove the specified contribution for a specified user
     *
     * @param  int $user_id
     * @param  int $project_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $project_id)
    {
        Project_user::where([
            ['project_id', '=', $project_id],
            ['user_id', '=', $user_id]
        ])->firstOrFail()->delete();
        return Project_user::all();
    }
}
