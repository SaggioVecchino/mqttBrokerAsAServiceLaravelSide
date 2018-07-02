<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProjectController extends Controller
{
    
    public function add(Request $request)
    {
        $validatedData = $this->validate($request, [
            'project_name' => 'required|string|max:255|min:5',
            'password' => 'required|string|min:6'
        ]);

        Project::create(
            [
                'project_name' => request('project_name'),
                'password' => Hash::make(md5(request('password')))
            ]
        );

        return Project::all();
    }


}
