@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                Create your new project
                </div>
                <div class="card-body">
                {{ Form::open(array('url' => '/projects')) }}
                {{ Form::text('project_name', '', array('placeholder' => 'Project name')) }}
                <br/>
                {{ Form::password('password', array('placeholder' => 'password')) }}
                <br>
                {{ Form::submit('submit') }}
                {{ Form::close() }}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
