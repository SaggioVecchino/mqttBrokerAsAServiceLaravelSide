<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device_group;
use App\Device;
use App\device_groups_topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeviceGroupController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('userHasGroup:' . Route::input('device_group'), ['except' => [
            'index',
            'create',
            'store'
        ]]);
        $this->middleware('userHasProject:' . request('project_id'), ['only' => [
            'create',
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
        return Device_group::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate(
            $request,
            [
                "project_id" => "required|Integer|min:1",
            ]
        );
        $project_id = request('project_id');
        return view('create_new_group', compact('project_id'));
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
                "project_id" => "required|Integer|min:1",
                "group_name" => "required|string|min:5|max:255",
            ]
        );

        try {
            $group_id = (Device_group::create(
                [
                    'group_name' => request('group_name'),
                    'project_id' => request('project_id')
                ]
            ))->id;
        } catch (QueryException $e) {
            return Redirect::back()->withErrors(['Group name: "' . request('group_name') . '" exists in the project with the ID: ' . request('project_id')]);
        }
        return redirect('/device_groups/' . $group_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Device_group::findOrFail($id);
        $devices = Device::where('group_id', $id)
            ->select('device_name')
            ->get();
        $permissionsPublications = DB::table('device_groups_topics')
            ->where([
                ['device_groups_topics.group_id', '=', $id],
                ['device_groups_topics.allow', '=', true],
                ['device_groups_topics.type', '=', 'publication']
            ])
            ->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get();
        $prohibitionsPublications = DB::table('device_groups_topics')
            ->where([
                ['device_groups_topics.group_id', '=', $id],
                ['device_groups_topics.allow', '=', false],
                ['device_groups_topics.type', '=', 'publication']
            ])
            ->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get();
        $permissionsSubscribtions = DB::table('device_groups_topics')
            ->where([
                ['device_groups_topics.group_id', '=', $id],
                ['device_groups_topics.allow', '=', true],
                ['device_groups_topics.type', '=', 'subscribtion']
            ])
            ->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get();
        $prohibitionsSubscribtions = DB::table('device_groups_topics')
            ->where([
                ['device_groups_topics.group_id', '=', $id],
                ['device_groups_topics.allow', '=', false],
                ['device_groups_topics.type', '=', 'subscribtion']
            ])
            ->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get();


        return view(
            'group',
            compact(
                'group',
                'devices',
                'permissionsPublications',
                'prohibitionsPublications',
                'permissionsSubscribtions',
                'prohibitionsSubscribtions'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Device_group::findOrFail($id);
        return view('edit_group', compact('group'));
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
        $group = Device_group::findOrFail($id);
        $this->validate(
            $request,
            [
                "group_name" => "required|string|max:255|min:5",
            ]
        );

        try {
            Device_group::where(
                [
                    ['group_name', '=', request('group_name')],
                    ['project_id', '=', $group->project_id]
                ]
            )->firstOrFail();
        } catch (ModelNotFoundException $e) {
            //Group name doen't exist in the project
            $group->update($request->only('group_name'));
            return redirect('/device_groups/' . $id);
        }

        //Group name exists in the project
        return Redirect::back()->withErrors(['Group name: "' . request('group_name') . '" exists in the project with the ID: ' . request('project_id')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Device_group::findOrFail($id)->delete();
        return Device_group::all();
    }
}
