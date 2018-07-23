@extends('layouts.app')

@section('content')

    <div class="container" >
    <a href="/" class="btn">QUIT!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="card">
                <div class="card-header"  id="form-div">My Projects
                    <br><br>
                    {{--<a href="/projects/create">Create a new project</a>--}}
                     <newprojectform></newprojectform>

                </div>
                <div class="card-body">
                    @foreach($projects as $project)
                        <div class="card">
                            <div class="card-header"><b>ID:</b> {{ $project->id }}<br><b>Project name:</b> {{ $project->project_name }}</div>
                            <div class="card-body">
                                <ul>
                                    <li><a href="/projects/{{$project->id}}/specify_data">show statistics</a></li>
                                    <li><a href="/projects/{{$project->id}}">show groups</a></li>
                                    {{--<li><a href="/projects/{{$project->id}}/edit">edit</a></li>--}}
                                </ul>
                                <editprojectname project_id="{{$project->id}}" project_name="{{ $project->project_name }}"></editprojectname>
                                <editprojectpassword project_id="{{$project->id}}" project_name="{{ $project->project_name }}"></editprojectpassword>
                                <div class="btn-group dropright">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        edit
                                    </button>
                                    <div class="dropdown-menu" >
                                        <button type="button" data-toggle="modal" data-target="#{{$project->project_name }}{{$project->id}}edit_name">name</button>
                                        <button type="button" data-toggle="modal" data-target="#{{$project->project_name }}{{$project->id}}edit_password">password</button>
                                    </div>
                                </div>
                        </div>
                    </div>
                    @endforeach

            </div>
            </div>
        </div>
    </div>
</div>
@endsection