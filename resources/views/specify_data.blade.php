@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects/{{$project->id}}" class="btn">Go back!</a>
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
                    Specify data you want to get from the project:<br>
                    <b>ID:</b> {{$project->id}}<br>
                    <b>Project name:</b> {{$project->project_name}}
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => '/projects/'.$project->id.'/show_data')) }}
                    {{ Form::text('topics[]','',array('placeholder' => 'topic_name')) }}
               <!--     <br>
                    {{ Form::text('topics[]','',array('placeholder' => 'topic_name')) }}
                    <br><br><br>
                    {{ Form::text('devices[0][group_name]','',array('placeholder' => 'group_name')) }}
                    <br>
                    {{ Form::text('devices[0][device_name]','',array('placeholder' => 'device_name')) }}
                    <br><br>
                    {{ Form::text('devices[1][group_name]','',array('placeholder' => 'group_name')) }}
                    <br>
                    {{ Form::text('devices[1][device_name]','',array('placeholder' => 'device_name')) }}
                    <br><br>
                    {{ Form::text('devices[2][group_name]','',array('placeholder' => 'group_name')) }}
                    <br>
                    {{ Form::text('devices[2][device_name]','',array('placeholder' => 'device_name')) }}
                    <br><br><br>!-->
                    <b>Interval :</b>{{Form::select('interval', array('Y' => 'Year', 'M' => 'Month',
                        'W' => 'Week', 'D' => 'Day', 'H' => 'Hour'))}}
                    <br>
                    <b>Frequence: </b>{{Form::select('freq', array('M' => 'Month',
                        'W' => 'Week', 'D' => 'Day', 'H' => 'Hour', 'Mn' => 'Minute'))}}
                    <br>
                    <b>Aggregate: </b>{{Form::select('agg', array('min' => 'min', 'max' => 'max',
                        'count' => 'count', 'sum' => 'sum', 'avg' => 'avg'))}}
                        
                    <br>
                    {{ Form::submit('OK') }}
                    {{ Form::close() }} 
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
