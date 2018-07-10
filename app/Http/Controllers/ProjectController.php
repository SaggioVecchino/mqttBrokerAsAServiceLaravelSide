<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{

    public function add(Request $request)
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
        // return Project::all();
    }

    public function edit($project_id, Request $request)
    {
        $project = null;
        try {
            $project = Project::findOrFail($project_id);
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->withErrors(['msg', 'project_id doesn\'t exist']);
        }
        if (request('new_password') != null) {
            $this->validate($request, [
                'old_password' => 'required|string|min:6',
                'new_password' => 'required|string|min:6'
            ]);

            if (Hash::check(request('old_password'), $project->password)) {
                $project->update(['password' => Hash::make(request('new_password'))]);
            } else {
                return Redirect::back()->withErrors(['msg', 'Incorrect password..']);
            }
        } else {
            $this->validate($request, [
                'project_name' => 'required|string|min:6'
            ]);
            $project->update(['project_name' => request('project_name')]);
        }
        return Project::all();
    }


}
