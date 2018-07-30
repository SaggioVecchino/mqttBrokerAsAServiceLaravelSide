<?php

namespace App\Http\Controllers;

use App\Device_groups_topic;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Topic;
use App\Device_group;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;

class Device_groups_topicController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware(
            'userHasDeviceGroupTopic:' . Route::input('device_group_topic'),
            ['except' => [
                'index',
                'create',
                'store',
                'authorizePublish',
                'authorizeSubscribe'
            ]]
        );
        $this->middleware('userHasGroup:' . request('group_id'), ['only' => [
            'store',
            'create'
        ]]);
    }


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
    public function create(Request $request)
    {
        $this->validate(
            $request,
            [
                "allow" => "required|Boolean",
                "type" => "required|in:publication,subscribtion",
                "group_id" => "required|Integer|min:1"
            ]
        );
        $group_name = (Device_group::findOrFail($request->group_id))->group_name;
        $project_id = (Device_group::findOrFail($request->group_id))->project_id;

        /*$toAvoid = array_map(function ($dgt) {
            return $dgt->topic_id;
        }, DB::table('device_groups_topics')->where([
            ['type', '=', request('type')],
            ['allow', '=', !request('allow')],
            ['group_id', '=', request('group_id')],
        ])->select('topic_id')->get()->toArray());*/

        $topics = DB::table('topics')->where('project_id', '=', $project_id)
            //->whereNotIn('id', $toAvoid)
            ->select('topic_name')->get()->toArray();

        $topics_names = array_map(function ($topic) {
            return $topic->topic_name;
        }, $topics);

        $values = array_values($topics_names);
        $stringKeys = array_map('strval', $values);

        $topics_names = array_combine($stringKeys, $values);
        return compact('topics_names','group_name');
      //  return $topics_names;
     //  return view('create_permissionprohibition', compact('request', 'group_name', 'topics_names'));
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
                "group_id" => "required|Integer|min:1",
                'topic_name' => [
                    'required',
                    'string',
                    'min:1',
                    'max:255',
                    'regex:/^(([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?|#)$/'
                ],
            ]
        );

        $project_id = (Device_group::findOrFail(request('group_id')))->project_id;
        $topic_id = 0;

        try {

            $topic_id = (Topic::where('topic_name', '=', request('topic_name'))
                ->firstOrFail())->id;
        } catch (ModelNotFoundException $e) {
            $topic_id = (Topic::create([
                'topic_name' => request('topic_name'),
                'project_id' => $project_id
            ]))->id;
        }
    try{
        Device_groups_topic::create(
            [
                'group_id' => request('group_id'),
                'project_id' => $project_id,
                'topic_id' => $topic_id,
                'allow' => request('allow'),
                'type' => request('type')
            ]
        );
    }
    catch (QueryException $e){
            return response(
                ['errors'=>["topic_name"=>["choose between topics that haven't yet been selected for the opposite permission"] ]],
                401);
    }
    if ($request->wantsJson())
            return '/device_groups/'.request('group_id');
    return redirect('/device_groups/' . request('group_id'));
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

    function authorizePublish($project_id, $group_name, Request $request)
    {
        $this->validate(
            $request,
            [
                'topic' => [
                    'required',
                    'string',
                    'min:1',
                    'max:255',
                    'regex:/^([\w ]+)(\/([\w ]+))*$/'
                ]
            ]
        );
        try {
            $group_id = Device_group::where([
                ['group_name', '=', $group_name],
                ['project_id', '=', $project_id]
            ])->firstOrFail()->id;
        } catch (ModelNotFoundException $e) {
            //project-group problem
            $flag = [
                'flag' => false,
                'message' =>
                    'Project: ' . 'project_id' . ' doesn\'t exist or/and Group '
                    . 'group_name' . ' doesn\'t in the project'
            ];
            return $flag;
        }
        $disallowed = DB::table('device_groups_topics')->where([
            ['device_groups_topics.project_id', '=', $project_id],
            ['device_groups_topics.group_id', '=', $group_id],
            ['device_groups_topics.allow', '=', false],
            ['device_groups_topics.type', '=', 'publication']
        ])->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get()
            ->first(function ($prohibition) {
                return preg_match(
                    Topic::topicToRegEx($prohibition->topic_name, false),
                    request("topic")
                );
            });
        if ($disallowed) {
            $flag = [
                "flag" => false,
                "message" => "disallowed"
            ];
            return $flag;
        }
        $allowed = DB::table('device_groups_topics')->where([
            ['device_groups_topics.project_id', '=', $project_id],
            ['device_groups_topics.group_id', '=', $group_id],
            ['device_groups_topics.allow', '=', true],
            ['device_groups_topics.type', '=', 'publication']
        ])->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get()
            ->first(function ($permission) {
                return preg_match(
                    Topic::topicToRegEx($permission->topic_name, true),
                    request("topic")
                );
            });
        if ($allowed) {
            $flag = [
                "flag" => true,
                "message" => "allowed"
            ];
            return $flag;
        }
        $flag = [
            "flag" => false,
            "message" => "disallowed"
        ];
        return $flag;
    }

    function authorizeSubscribe($project_id, $group_name, Request $request)
    {
        $this->validate(
            $request,
            [
                'topic' => [
                    'required',
                    'string',
                    'min:1',
                    'max:255',
                    'regex:/^(([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?|#)$/'
                ]
            ]
        );
        try {
            $group_id = Device_group::where([
                ['group_name', '=', $group_name],
                ['project_id', '=', $project_id]
            ])->firstOrFail()->id;
        } catch (ModelNotFoundException $e) {
            //project-group problem
            $flag = [
                'flag' => false,
                'message' =>
                    'Project: ' . 'project_id' . ' doesn\'t exist or/and Group '
                    . 'group_name' . ' doesn\'t in the project'
            ];
            return $flag;
        }
        $disallowed = DB::table('device_groups_topics')->where([
            ['device_groups_topics.project_id', '=', $project_id],
            ['device_groups_topics.group_id', '=', $group_id],
            ['device_groups_topics.allow', '=', false],
            ['device_groups_topics.type', '=', 'subscribtion']
        ])->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get()
            ->first(function ($prohibition) {
                return preg_match(
                    Topic::topicToRegEx($prohibition->topic_name, false),
                    request("topic")
                );
            });
        if ($disallowed) {
            $flag = [
                "flag" => false,
                "message" => "disallowed"
            ];
            return $flag;
        }
        $allowed = DB::table('device_groups_topics')->where([
            ['device_groups_topics.project_id', '=', $project_id],
            ['device_groups_topics.group_id', '=', $group_id],
            ['device_groups_topics.allow', '=', true],
            ['device_groups_topics.type', '=', 'subscribtion']
        ])->join('topics', 'topics.id', '=', 'device_groups_topics.topic_id')
            ->select('topics.topic_name')
            ->get()
            ->first(function ($permission) {
                return preg_match(
                    Topic::topicToRegEx($permission->topic_name, true),
                    request("topic")
                );
            });
        if ($allowed) {
            $flag = [
                "flag" => true,
                "message" => "allowed"
            ];
            return $flag;
        }
        $flag = [
            "flag" => false,
            "message" => "disallowed"
        ];
        return $flag;
    }
}
