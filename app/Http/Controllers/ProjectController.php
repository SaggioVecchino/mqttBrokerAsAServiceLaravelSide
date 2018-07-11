<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'project_name' => 'required|string|max:255|min:5',
            'password' => 'required|string|min:6'
            //we have to add the user authenticated as owner
        ]);

        Project::create(
            [
                'project_name' => request('project_name'),
                'password' => Hash::make(request('password'))
            ]
        );
        return redirect('/projects');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Project::findOrFail($id);
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

    public function changeUsername($id, Request $request){
        $this->validate($request, [
            'project_name' => 'required'
        ]);
        return $this->update($request, $id);
    }

    public function changePassword($id, Request $request){
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
