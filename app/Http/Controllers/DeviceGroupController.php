<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device_group;

class DeviceGroupController extends Controller
{
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
                "project_id" => "required|Integer|min:1",
                "group_name" => "required|string|min:5|max:255",
            ]
        );
        Device_group::create(
            [
                'group_name' => request('group_name'),
                'project_id' => request('project_id')
            ]
        );
        return Device_group::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Device_group::findOrFail($id);
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
        $group = Device_group::findOrFail($id);
        $this->validate(
            $request,
            [
                "group_name" => "required|string|max:255|min:5",
            ]
        );
        $group->update($request->only('group_name'));
        return Device_group::all();
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
