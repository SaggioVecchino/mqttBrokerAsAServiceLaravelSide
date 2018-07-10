<?php

namespace App\Http\Controllers;

use App\Project;
use App\Project_user;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{

    public function add(Request $request)
    {
        $validatedData = $this->validate($request, [
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

    function show(){
        return Project::all();
    }


    public function delete($project_id){
        try{
            $project=Project::findOrFail($project_id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"you are trying to delete an inexistant project"]);

        }
        $project->delete();
        return Project::all();
    }




}
