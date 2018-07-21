@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects" class="btn">Go back!</a>
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
                
                <div class="card-header">
                Change <b>project name</b>
                </div>
                <div class="card-body">
                {{ Form::open(array('url' => '/projects/'.$project->id.'/change_project_name', 'method' => 'patch')) }}
                {{ Form::text('project_name', $project->project_name, array('placeholder' => 'Project name')) }}
                <br>
                {{ Form::submit('OK') }}
                {{ Form::close() }}
                </div>



                <div class="card-header">
                Change <b>password</b>
                </div>
                <div class="card-body">
                {{ Form::open(array('url' => '/projects/'.$project->id.'/change_password', 'method' => 'patch')) }}
                {{ Form::password('old_password',array('placeholder' => 'Old password'))}}
                <br>
                {{ Form::password('password', array('placeholder' => 'New password')) }}
                <br/>
                {{ Form::submit('OK') }}
                {{ Form::close() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
