@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects" class="btn">Go back!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                Groups of devices in the project:<br>
                <b>ID:</b> {{$project->id}}<br>
                <b>Project name:</b> {{$project->project_name}}
                <br><a href="/device_groups/create?project_id={{$project->id}}">Create a new group</a>
                </div>
                @foreach($groups as $group)
                <div class="card-header"><b>Id: </b>{{ $group->id }} <br> <b>Group name: </b> {{ $group->group_name }}</div>
                <div class="card-body">
                <ul>
                    <li><a href="/device_groups/{{$group->id}}">show</a></li>
                    <li><a href="/device_groups/{{$group->id}}/edit">edit</a></li>
                </ul>
                    
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
