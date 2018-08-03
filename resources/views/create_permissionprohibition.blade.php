@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/device_groups/{{$request->group_id}}" class="btn btn btn-outline-danger">Go back!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create/Add a new {{$request->type}} for the group {{$group_name}}</div>
                <div class="card-header">
                    Create a new topic name
                </div>
                <div class="card-body">
                {{ Form::open(array('url' => '/device_groups_topics')) }}
                {{ Form::text('topic_name') }}
                {{ Form::hidden('group_id', $request->group_id) }}
                {{ Form::hidden('allow', $request->allow) }}
                {{ Form::hidden('type', $request->type) }}
                {{ Form::submit('submit') }}
                {{ Form::close() }}
                </div>
                
                <div class="card-header">
                    Choose a topic
                </div>
                <div class="card-body">
                {{ Form::open(array('url' => '/device_groups_topics')) }}
                {{ Form::select('topic_name', $topics_names) }}
                {{ Form::hidden('group_id', $request->group_id) }}
                {{ Form::hidden('allow', $request->allow) }}
                {{ Form::hidden('type', $request->type) }}
                {{ Form::submit('submit') }}
                {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
