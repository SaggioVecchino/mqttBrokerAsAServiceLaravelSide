<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
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
                    'regex:/^([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?$/'
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
        try{
            $topic= Topic::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"the topic with the specified id does not exist"]);
        }
        return $topic;
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
                    'regex:/^([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?$/'
                ],
                'project_id' => 'required|Integer|min:1'
            ]
        );
        try{
            $topic= Topic::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"you are trying to update an inexistant topic"]);
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
        try{
            $topic= Topic::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return Redirect::back()->withErrors(["msg"=>"you are trying to delete an inexistant topic"]);
        }
        $topic->delete();
        return Topic::all();
    }


    static function topicToRegEx($topic, $allow){
        return $allow ? self::permissionToRegEx($topic) : self::prohibitionToRegEx($topic);
    }

    private static function permissionToRegEx($topic)
    {
        $fields = explode('/', $topic);
        $regEx = '';
        foreach ($fields as $field) {
            if ($field == '+') {
                $regEx .= '([\\w ]+|\\+)\\/';
            } elseif ($field == '#') {
                $regEx .= '([\\w ]+|\\+)(\\/([\w ]+|\\+))*(\\/\\#)?';
            } else {
                $regEx .= $field . '\\/';
            }
        }
        $len = strlen($regEx);
        if ($regEx {$len - 1} == '/')
            $regEx = substr($regEx, 0, $len - 2);
        $regEx = '/^' . $regEx . '$/';
        return $regEx;
    }

    private static function prohibitionToRegEx($topic)
    {
        $fields = explode('/', $topic);
        $regEx = '';
        if(count($fields))
            $regEx = '(\\#|';
        foreach ($fields as $field) {
            if ($field == '+') {
                $regEx .= '(([\\w ]+\\/?|\\+\\/?)(\\#|';
            } elseif ($field == '#') {
                $regEx .= '((([\\w ]+|\\+)(\\/([\w ]+|\\+))*(\\/\\#)?)(\\#|';
            } else {
                $regEx .= '((' . $field . '\\/?|\\+\\/?)(\\#|';
            }
        }

        $regEx = substr($regEx, 0, strlen($regEx) - 1);

        for($i = 0 ; $i < 2*count($fields) ; $i ++)
            $regEx .= ')?';
        if(count($fields))
            $regEx .= ')';
        $regEx = '/^' . $regEx . '$/';
        return $regEx;
    }




}
