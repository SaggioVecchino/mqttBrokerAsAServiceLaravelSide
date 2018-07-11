<?php

namespace App\Http\Controllers;

use App\Device_groups_topic;
use Illuminate\Http\Request;

class Device_groups_topicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Device_groups_topic::all();
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
        $this->validate(
            $request,
            [
                "allow" => "required|Boolean",
                "type" => "required|in:publication,subscribtion",
                "topic_id" => "required|Integer|min:1",
                "project_id" => "required|Integer|min:1",
                "group_id" => "required|Integer|min:1"
            ]
        );
        // we have to deal with the case in which topic don't have the same project_id here
        Device_groups_topic::create(
            [
                "group_id" => request("group_id"),
                "project_id" => request("project_id"),
                "topic_id" => request("topic_id"),
                "allow" => request("allow"),
                "type" => request("type")
            ]
        );
        return Device_groups_topic::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Device_groups_topic::findOrFail($id);
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
        $this->validate(
            $request,
            [
                "allow" => "Boolean",
                "type" => "in:publication,subscribtion",
            ]
        );
        $device_group_topic = Device_groups_topic::findOrFail($id)->update($request->only(["allow", "type"]));
        return Device_groups_topic::all();
    }

    public function changeType(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required',
        ]);
        $this->update($request, $id);
    }
    public function changeVerdict(Request $request, $id)
    {
        $this->validate($request, [
            'allow' => 'required',
        ]);
        $this->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Device_groups_topic::findOrFail($id)->delete();
        return Device_groups_topic::all();
    }
}
