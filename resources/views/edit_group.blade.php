@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects/{{$group->project_id}}" class="btn btn btn-outline-danger">Go back!</a>
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
                Change <b>group name</b>
                </div>
                <div class="card-body">
                {{ Form::open(array('url' => '/device_groups/'.$group->id, 'method' => 'patch')) }}
                {{ Form::text('group_name', $group->group_name, array('placeholder' => 'Group name')) }}
                <br>
                {{ Form::submit('OK') }}
                {{ Form::close() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
