<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Project_user;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Topic;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Route;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userHasProject:' . Route::input('project'), ['except' => [
            'index',
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

        $projects = DB::table('users')
            ->select('users.id')
            ->where('users.id', Auth::id())
            ->join('project_users', 'project_users.user_id', '=', 'users.id')
            ->select('project_users.project_id')
            ->join('projects', 'projects.id', '=', 'project_users.project_id')
            ->select('projects.id', 'projects.project_name')
            ->get();
        return view('home', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_new_project');
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
            'project_name' => 'required|string|max:255|min:5',
            'password' => 'required|string|min:6'
            //we have to add the user authenticated as owner
        ]);

        $project_id = Project::create(
            [
                'project_name' => request('project_name'),
                'password' => Hash::make(request('password'))
            ]
        )->id;

        Project_user::create(
            [
                'user_id' => Auth::id(),
                'project_id' => $project_id
            ]
        );
        if ($request->wantsJson())
            return ('/projects/' . $project_id);
        return redirect('/projects/' . $project_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $project_id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id)
    {
        $project = Project::findOrFail($project_id);

        $groups = DB::table('projects')
            ->where('projects.id', $project_id)
            ->join('device_groups', 'device_groups.project_id', '=', 'projects.id')
            ->get();

        return view('project', compact('groups', 'project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $project_id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id)
    {
        $project = Project::findOrFail($project_id);
        return view('edit_project', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $project_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id)
    {
        $this->validate($request, [
            'project_name' => 'string|max:255|min:5',
            'password' => 'string|min:6'
        ]);

        $project = Project::findOrFail($project_id);
        $req = $request->only(['project_name', 'password']);
        if (array_key_exists('password', $req))
            $req['password'] = Hash::make($req['password']);
        $project->update($req);
        return Project::all();
    }

    public function changeProjectName($project_id, Request $request)
    {
        $this->validate($request, [
            'project_name' => 'required'
        ]);
        return $this->update($request, $project_id);
    }

    public function changePassword($project_id, Request $request)
    {
        try {
            $project = Project::findOrFail($project_id);
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->withErrors(['project_id doesn\'t exist']);
        }
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required'
        ]);
        if (Hash::check(request('old_password'), $project->password)) {
            return $this->update($request, $project_id);
        } else {
            if ($request->wantsJson())
                return response(
                ['errors' => ["old_password" => ["Incorrect password"]]],
                402
            );
            return Redirect::back()->withErrors(['Incorrect password']);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $project_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id)
    {
        Project::findOrFail($project_id)->delete();
        return Project::all();
    }

    public function specify_data($project_id)
    {
        $project = Project::findOrFail($project_id);
        return view('specify_data', compact('project'));
    }

    /**
     * get data specified by the request
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_data($project_id, Request $request)
    {

        $now = time();

        Validator::make(array('project_id' => $project_id), [
            'project_id' => 'required|numeric|min:1',
        ])->validate();

        $interval_enum = ['Y', 'M', 'W', 'D', 'H'];
        $freq_enum = ['M', 'W', 'D', 'H', 'Mn'];
        $this->validate($request, [
            'requestSets' => 'required|array',
            'requestSets.*.devices' => 'array',
            'requestSets.*.devices.*' => 'required|array',
            'requestSets.*.devices.*.group_name' => 'required|string|max:255|min:5',
            'requestSets.*.devices.*.device_name' => 'string|min:5|max:255',
            'requestSets.*.topics' => 'required|array|min:1',
            'requestSets.*.topics.*' => [
                'required',
                'string',
                'min:1',
                'max:255',
                'regex:/^(([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?|#)$/'
            ],
            'interval' => [
                'required',
                Rule::in($interval_enum),
            ],
            'freq' => [
                'required',
                Rule::in($freq_enum),
            ],
            'agg' => [
                'required',
                Rule::in(['min', 'max', 'count', 'avg', 'sum']),
            ],
            'type' => [
                'required',
                Rule::in(['line', 'bar']),
            ]
        ]);

        $interval = request('interval');
        Validator::make($request->only('freq'), [
            'freq' => [
                function ($attribute, $value, $fail)
                    use ($interval, $interval_enum, $freq_enum) {
                    if ($value !== 'Mn' &&
                        array_search($value, $freq_enum)
                        < array_search($interval, $interval_enum)) {
                        return $fail('Invalid combination of interval and frequence.');
                    }
                },
            ],
        ])->validate();

        $requestSets = array_values(request('requestSets'));
        //to be sure we have indexes 0.. 1.. 2.. 3.. (escape any tentative aiming to break the code)

        $body = array();

        $body['time'] = $now;
        $body['project_id'] = $project_id;
        $body['interval'] = 'T' . $interval;
        $body['freq'] = request('freq');
        $body['agg'] = request('agg');

        for ($l = 0; $l < count(request('requestSets')); $l++) {
            $body['requestSets'][$l]['topics'] =
                Topic::topicsToRegEx(array_unique($requestSets[$l]['topics']));
            try {
                if (isset($requestSets[$l]['devices'])) {
                    $devices = array_values($requestSets[$l]['devices']);
                    //to be sure we have indexes 0.. 1.. 2.. 3.. (escape any tentative aiming to break the code)

                    $groups = array();
                    $potentielDoublon = array();
                    $count1 = count($devices);
                    $count2 = $count1;
                    for ($i = 0; $i < $count1; $i++) {
                        if (!array_key_exists('device_name', $devices[$i])) {
                            if (!in_array($devices[$i]['group_name'], $groups)) {
                                $groups[] = $devices[$i]['group_name'];
                            }
                        } else {
                            if (!array_key_exists($devices[$i]['group_name'], $potentielDoublon)) {
                                $potentielDoublon[$devices[$i]['group_name']] = array($devices[$i]['device_name']);
                            } elseif (in_array($devices[$i]['device_name'], $potentielDoublon[$devices[$i]['group_name']])) {
                                unset($devices[$i]);
                                $count2--;
                            }
                        }
                    }

                    $devices = array_values($devices);//we didn't use array_splice()

                    for ($i = 0; $i < $count2; $i++) {
                        if (!array_key_exists('device_name', $devices[$i])
                            || in_array($devices[$i]['group_name'], $groups)) {
                            unset($devices[$i]);
                        }
                    }

                    if (count($groups))
                        $body['requestSets'][$l]['groups'] = $groups;

                    if (count($devices))
                        $body['requestSets'][$l]['devices'] = array_values($devices);//we didn't use array_splice()
                }
            } catch (\Exception $e) {
                return Redirect::back()->withErrors(['Device specified without his group name !']);
            }
        }

        try {

            $requestContent = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json; charset=UTF-8'
                ],
                'json' => $body
            ];

            $link = '192.168.1.114:1233';

            $response['series'] = json_decode((new Client())->request('POST', 'http://' . $link . '/data/' . $project_id, $requestContent)
                ->getBody()->getContents());
            $response['requestSets'] = $requestSets;//On peut optimiser
            $response['agg'] = request('agg');

            $type = request('type');
            $freq = request('freq');
            return view('show_data', compact('response', 'project_id', 'type', 'interval', 'freq'));
        } catch (RequestException $re) {
            return Redirect::back()->withErrors(['Error while handling the request, please try again!']);
        }


            // test
        /* $response = [[
            ['x' => 'Jul 24 2018 13:16:17 GMT+0100', 'y' => 15],
            ['x' => 'Jul 25 2018 15:29:38 GMT+0100', 'y' => 10],
            ['x' => 'Jul 26 2018 13:18:45 GMT+0100', 'y' => 12],
            ['x' => 'Jul 27 2018 19:16:52 GMT+0100', 'y' => 7],
            ['x' => 'Jul 28 2018 10:16:26 GMT+0100', 'y' => 16],
            ['x' => 'Jul 29 2018 23:18:12 GMT+0100', 'y' => 23],
            ['x' => 'Jul 15 2018 23:18:12 GMT+0100', 'y' => 23],
            ['x' => 'Jul 20 2018 10:16:26 GMT+0100', 'y' => 16],
        ]];
        $type = request('type');
        $freq = request('freq');
        return view('show_data', compact('response', 'project_id', 'type', 'interval', 'freq')); */
            // testfin


    }

    public function update_data($project_id, Request $request)
    {
        Validator::make(array('project_id' => $project_id), [
            'project_id' => 'required|numeric|min:1',
        ])->validate();

        $enum = ['Y', 'M', 'W', 'D', 'H', 'Mn'];
        $this->validate($request, [
            'requestSets.*.devices' => 'array',
            'requestSets.*.groups' => 'array',
            'requestSets.*.groups.*' => 'required|string|max:255|min:5',
            'requestSets.*.devices.*' => 'required|array',
            'requestSets.*.devices.*.group_name' => 'required|string|max:255|min:5',
            'requestSets.*.devices.*.device_name' => 'required|string|min:5|max:255',
            'requestSets.*.topics' => 'required|array|min:1',
            'requestSets.*.topics.*' => [
                'required',
                'string',
                'min:1',
                'max:255',
                'regex:/^(([\w ]+|\+)(\/([\w ]+|\+))*(\/\#)?|#)$/'
            ],
            'freq' => [
                'required',
                Rule::in($interval_freq_enum),
            ],
            'agg' => [
                'required',
                Rule::in(['min', 'max', 'count', 'avg', 'sum']),
            ],
            'type' => [
                'required',
                Rule::in(['line', 'bar']),
            ]
        ]);

        $interval = request('freq');//interval === freq
        
        //to be sure we have indexes 0.. 1.. 2.. 3.. (escape any tentative aiming to break the code)

        $body = array();

        $body['time'] = $now;
        $body['project_id'] = $project_id;
        $body['interval'] = 'T' . $interval;
        $body['freq'] = request('freq');
        $body['agg'] = request('agg');



        for ($l = 0; $l < count(request('requestSets')); $l++) {
            $body['requestSets'][$l]['topics'] =
                Topic::topicsToRegEx(array_unique($requestSets[$l]['topics']));
            try {
                if (isset($requestSets[$l]['devices'])) {
                    $devices = array_values($requestSets[$l]['devices']);
                    //to be sure we have indexes 0.. 1.. 2.. 3.. (escape any tentative aiming to break the code)

                    $groups = array();
                    $potentielDoublon = array();
                    $count1 = count($devices);
                    $count2 = $count1;
                    for ($i = 0; $i < $count1; $i++) {
                        if (!array_key_exists('device_name', $devices[$i])) {
                            if (!in_array($devices[$i]['group_name'], $groups)) {
                                $groups[] = $devices[$i]['group_name'];
                            }
                        } else {
                            if (!array_key_exists($devices[$i]['group_name'], $potentielDoublon)) {
                                $potentielDoublon[$devices[$i]['group_name']] = array($devices[$i]['device_name']);
                            } elseif (in_array($devices[$i]['device_name'], $potentielDoublon[$devices[$i]['group_name']])) {
                                unset($devices[$i]);
                                $count2--;
                            }
                        }
                    }

                    $devices = array_values($devices);//we didn't use array_splice()

                    for ($i = 0; $i < $count2; $i++) {
                        if (!array_key_exists('device_name', $devices[$i])
                            || in_array($devices[$i]['group_name'], $groups)) {
                            unset($devices[$i]);
                        }
                    }

                    if (count($groups))
                        $body['requestSets'][$l]['groups'] = $groups;

                    if (count($devices))
                        $body['requestSets'][$l]['devices'] = array_values($devices);//we didn't use array_splice()
                }
            } catch (\Exception $e) {
                abort(400);
                    // return Redirect::back()->withErrors(['Device specified without his group name !']);
            }
        }


        try {
            $requestContent = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json; charset=UTF-8'
                ],
                'json' => $body
            ];

            $link = '192.168.1.114:1233';

            $response = json_decode((new Client())->request('POST', 'http://' . $link . '/data/' . $project_id, $requestContent)
                ->getBody()->getContents());
            return response()->json($response);
                // return view('show_data', compact('response', 'project_id', 'type', 'interval', 'freq'));
        } catch (RequestException $re) {
            abort(500);
                // return Redirect::back()->withErrors(['Error while handling the request, please try again!']);
        }


        // test
        /* $response = [[
            ['x' => 'Jul 24 2018 13:16:17 GMT+0100', 'y' => 15],
            ['x' => 'Jul 25 2018 15:29:38 GMT+0100', 'y' => 10],
            ['x' => 'Jul 26 2018 13:18:45 GMT+0100', 'y' => 12],
            ['x' => 'Jul 27 2018 19:16:52 GMT+0100', 'y' => 7],
            ['x' => 'Jul 28 2018 10:16:26 GMT+0100', 'y' => 16],
            ['x' => 'Jul 29 2018 23:18:12 GMT+0100', 'y' => 23],
            ['x' => 'Jul 15 2018 23:18:12 GMT+0100', 'y' => 23],
            ['x' => 'Jul 20 2018 10:16:26 GMT+0100', 'y' => 16],
        ]];
        $type = request('type');
        $freq = request('freq');

        return view('show_data', compact('response', 'project_id', 'type', 'interval', 'freq')); */
        // testfin


    }

}