<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class TopicController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('userHasTopic:' . Route::input('topic'), ['except' => [
            'index',
            'create',
            'store'
        ]]);
        $this->middleware('userHasProject:' . request('project_id'), ['only' => ['store']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Topic::all();
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
        //we have to check for the rights of adding group_names
        $this->validate(
            $request,
            [
                'topic_name' => [
                    'required',
                    'string',
                    'min:1',
                    'max:255',
                    'regex:/^(([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?|#)$/'
                ],
                'project_id' => 'required|Integer|min:1'
            ]
        );
        Topic::create(
            [
                'topic_name' => request('topic_name'),
                'project_id' => request("project_id")
            ]
        );
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Topic::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $this->validate(
            $request,
            [
                'topic_name' => [
                    'required',
                    'string',
                    'min:1',
                    'max:255',
                    'regex:/^(([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?|#)$/'
                ],
                'project_id' => 'required|Integer|min:1'
            ]
        );
        try {
            $topic = Topic::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->withErrors(["msg" => "you are trying to update an inexistant topic"]);
        }
        $topic->update(["topic_name" => request("topic_name")]);
//      $topic->update($request->except(["_token"]));
        return Topic::all();
    }
//    public function changeTopicName()
//    {
//
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $topic = Topic::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->withErrors(["msg" => "you are trying to delete an inexistant topic"]);
        }
        $topic->delete();
        return Topic::all();
    }


}
