@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects" class="btn btn btn-outline-danger">Go back!</a>
    <div class="row justify-content-center">

        <div class="col-md-8">
            {{--<newgroupform project_id="{{$project->id}}">--}}
            {{--</newgroupform>--}}
            <div class="card">
                <grouplist :groups="{{($groups)->toJson()}}" :project="{{$project->toJson()}}">

                </grouplist>
            </div>
        </div>
    </div>
</div>
@endsection
