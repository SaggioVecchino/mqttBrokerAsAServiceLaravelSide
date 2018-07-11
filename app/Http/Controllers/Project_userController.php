<?php

namespace App\Http\Controllers;

use App\Project;
use App\Project_user;
use Illuminate\Http\Request;

class Project_userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project_user::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $project_user= Project_user::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"the project_user with the specified id does not exist"]);
        }
        return $project_user;
    }

    public function showContributors($project_id)
    {
        $users = DB::table("project_users")->where([
            ["project_users.project_id", '=', $project_id]
        ])->join("users", "users.id", "=", "project_users.user_id")
            ->select("users.id", "users.email", "users.name")
            ->get();
        return $users;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        try{
            $project_user= Project_user::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"you are trying to delete an inexistant project_user"]);
        }
        $project_user->delete();
        return Project_user::all();
    }


    public function destroyContributor(Request $request)
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