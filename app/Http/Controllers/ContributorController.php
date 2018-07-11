<?php

namespace App\Http\Controllers;

use App\Project_user;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    /**
     * Display a list of contributors on the project .
     * 
     * @param  int  $project_id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project_id)
    {
        return DB::table("project_users")->where([
            ["project_users.project_id", '=', $project_id]
        ])->join("users", "users.id", "=", "project_users.user_id")
            ->select("users.*")
            ->get();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $project_id
     * @param int $user_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $user_id)
    {
        $contributor = Project_user::where([
            ['project_id', '=', request('project_id')],
            ['user_id', '=', request('user_id')]
        ])->firstOrFail()->delete();
        return Project_user::all();
    }
}
