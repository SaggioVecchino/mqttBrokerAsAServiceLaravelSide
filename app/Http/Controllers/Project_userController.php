<?php

namespace App\Http\Controllers;

use App\Project;
use App\Project_user;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class Project_userController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userOwnsProject:' . request('project_id'), ['only' => [
            'store'
        ]]);
        $this->middleware('userExists:' . request('user_id'), ['only' => [
            'store'
        ]]);
    }
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
        try{
            Project_user::create(
                [
                    'user_id' => request('user_id'),
                    'project_id' => request('project_id')
                ]
            );
        }
        catch(QueryException $e){
            if ($request->wantsJson())
                return response(
                    ['errors'=>["name"=>["The user you're trying to add is already a contributor"] ]],
                    403);
            returnRedirect::back()->withErrors(["The user you're trying to add is already a contributor"]);
        }

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
        return Project_user::findOrFail($id);
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
        Project_user::findOrFail($id)->delete();
        return Project_user::all();
    }
}