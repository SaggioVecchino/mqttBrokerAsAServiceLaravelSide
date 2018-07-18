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

class ProjectController extends Controller
{
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
        if (Auth::check()) {
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
            return redirect('/projects/' . $project_id);
        } else
            return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);

        $groups = DB::table('projects')
            ->where('projects.id', $id)
            ->join('device_groups', 'device_groups.project_id', '=', 'projects.id')
            ->get();

        return view('project', compact('groups', 'project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('edit_project', compact('project'));
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
        $this->validate($request, [
            'project_name' => 'string|max:255|min:5',
            'password' => 'string|min:6'
        ]);

        $project = Project::findOrFail($id);
        $req = $request->only(['project_name', 'password']);
        if (array_key_exists('password', $req))
            $req['password'] = Hash::make($req['password']);
        $project->update($req);
        return Project::all();
    }

    public function changeProjectName($id, Request $request)
    {
        $this->validate($request, [
            'project_name' => 'required'
        ]);
        return $this->update($request, $id);
    }

    public function changePassword($id, Request $request)
    {
        try {
            $project = Project::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->withErrors(['msg', 'project_id doesn\'t exist']);
        }
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required'
        ]);
        if (Hash::check(request('old_password'), $project->password)) {
            return $this->update($request, $id);
        } else {
            return Redirect::back()->withErrors(['msg', 'Incorrect password']);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Project::findOrFail($id)->delete();
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
        Validator::make(array('project_id' => $project_id), [
            'project_id' => 'required|numeric|min:1',
        ])->validate();

        $interval_enum = ['Y', 'M', 'W', 'D', 'H'];
        $freq_enum = ['M', 'W', 'D', 'H', 'Mn'];

        $this->validate($request, [
            'devices' => 'array',
            'devices.*' => 'array',
            'devices.*.group_name' => 'string|max:255|min:5',
            'devices.*.device_name' => 'string|min:5|max:255',
            'topics' => 'required|array|min:1',
            'topics.*' => [
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

        $body = array();
        $body['project_id'] = $project_id;
        $body['topics'] = Topic::topicsToRegEx(array_unique(request('topics')));
        $body['interval'] = 'T' . request('interval');
        $body['freq'] = request('freq');
        $body['agg'] = request('agg');

        try {
            if (request('devices') != null) {
                $devices = array_values(request('devices'));
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
                    $body['groups'] = $groups;

                if (count($devices))
                    $body['devices'] = array_values($devices);//we didn't use array_splice()
            }
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['Device specified without his group name !']);
        }
        try {
            $requestContent = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json; charset=UTF-8'
                ],
                'json' => $body
            ];

            $link = '192.168.43.3:1233';

            $response = json_decode((new Client())->request('POST', 'http://' . $link . '/data/' . $project_id, $requestContent)
                ->getBody()->getContents());
            return view('show_data', compact('response','project_id'));
            //dd($response);
        } catch (RequestException $re) {
            //dd($re);
            return Redirect::back()->withErrors(['Error while handling the request, please try again!']);
        }



    }
}
