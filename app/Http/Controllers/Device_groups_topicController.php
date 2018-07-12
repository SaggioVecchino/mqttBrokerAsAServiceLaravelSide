<?php

namespace App\Http\Controllers;
use App\Device_group;
use App\Device_groups_topic;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                "project_id" =>request("project_id"),
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
        try{
            $device_group_topic= Device_groups_topic::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"the device_group_topic with the specified id does not exist"]);
        }
        return $device_group_topic;
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
             ]);
        try{
            $device_group_topic= Device_groups_topic::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"you are trying to update an inexistant topic"]);
        }
        $device_group_topic->update($request->only(["allow","type"]));
        return Device_groups_topic::all();
    }

    public function changeType(Request $request,$id){
        $this->validate($request, [
            'type' => 'required',
        ]);
        $this->update($request,$id);
    }
    public function changeVerdict(Request $request,$id){
        $this->validate($request, [
            'allow' => 'required',
        ]);
        $this->update($request,$id);
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
            $device_group_topic= Device_groups_topic::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"you are trying to delete an inexistant device_group_topic"]);
        }
        $device_group_topic->delete();
        return Device_groups_topic::all();
    }


}
