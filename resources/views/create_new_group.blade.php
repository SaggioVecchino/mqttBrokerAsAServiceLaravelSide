@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects/{{$project_id}}" class="btn">Go back!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                Create a new group in the project with <b>ID</b>: {{$project_id}}
                </div>
                <div class="card-body">
                {{ Form::open(array('url' => '/device_groups')) }}
                {{ Form::hidden('project_id', $project_id) }}
                {{ Form::text('group_name', '', array('placeholder' => 'Group name')) }}
                <br/>
                {{ Form::submit('submit') }}
                {{ Form::close() }}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
