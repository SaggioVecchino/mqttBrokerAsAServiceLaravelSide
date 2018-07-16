<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Project_user;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $projects = DB::table('users')
            ->select('users.id')
            ->where('users.id', Auth::id())
            ->join('project_users', 'project_users.user_id', '=', 'users.id')
            ->select('project_users.project_id')
            ->join('projects', 'projects.id', '=', 'project_users.project_id')
            ->select('projects.id', 'projects.project_name')
            ->get();
        return view('home', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_new_project');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $this->validate($request, [
                'project_name' => 'required|string|max:255|min:5',
                'password' => 'required|string|min:6'
            //we have to add the user authenticated as owner
            ]);

            $project_id = Project::create(
                [
                    'project_name' => request('project_name'),
                    'password' => Hash::make(request('password'))
                ]
            )->id;
            Project_user::create(
                [
                    'user_id' => Auth::id(),
                    'project_id' => $project_id
                ]
            );
            return redirect('/projects/'.$project_id);
        } else
            return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);

        $groups = DB::table('projects')
            ->where('projects.id', $id)
            ->join('device_groups', 'device_groups.project_id', '=', 'projects.id')
            ->get();        

        return view('project', compact('groups','project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('edit_project', compact('project'));
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
        $this->validate($request, [
            'project_name' => 'string|max:255|min:5',
            'password' => 'string|min:6'
        ]);

        $project = Project::findOrFail($id);
        $req = $request->only(['project_name', 'password']);
        if (array_key_exists('password', $req))
            $req['password'] = Hash::make($req['password']);
        $project->update($req);
        return Project::all();
    }

    public function changeProjectName($id, Request $request)
    {
        $this->validate($request, [
            'project_name' => 'required'
        ]);
        return $this->update($request, $id);
    }

    public function changePassword($id, Request $request)
    {
        try {
            $project = Project::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->withErrors(['msg', 'project_id doesn\'t exist']);
        }
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required'
        ]);
        if (Hash::check(request('old_password'), $project->password)) {
            return $this->update($request, $id);
        } else {
            return Redirect::back()->withErrors(['msg', 'Incorrect password']);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Project::findOrFail($id)->delete();
        return Project::all();
    }
}
