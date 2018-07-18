@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/" class="btn">QUIT!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My Projects
                <br> <a href="/projects/create">Create a new project</a></div>

                @foreach($projects as $project)
                <div class="card-header"><b>ID:</b> {{ $project->id }}<br><b>Project name:</b> {{ $project->project_name }}</div>
                <div class="card-body">
                <ul>
                    <li><a href="/projects/{{$project->id}}/specify_data">show statistics</a></li>
                    <li><a href="/projects/{{$project->id}}">show groups</a></li>
                    <li><a href="/projects/{{$project->id}}/edit">edit</a></li>
                </ul>
                    
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection