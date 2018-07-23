@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects" class="btn">Go back!</a>
    <div class="row justify-content-center">

        <div class="col-md-8">
            {{--<newgroupform project_id="{{$project->id}}">--}}
            {{--</newgroupform>--}}
            <div class="card">
                <div class="card-header">
                   <div class="container">
                       <div class="row">
                           <div class="col-lg-6">
                               Groups of devices in the project:
                               <br>
                               <b>ID:</b> {{$project->id}}<br>
                               <b>Project name:</b> {{$project->project_name}}
                               <br>
                               {{--<a href="/device_groups/create?project_id={{$project->id}}">Create a new group</a>--}}

                           </div>
                           <div class="col-lg-6">
                               <br>
                               <newgroupform project_id="{{$project->id}}">
                               </newgroupform>
                           </div>
                       </div>
                   </div>
                </div>

                <div class="card-body">
                        @foreach($groups as $group)
                        <div class="card">
                            <div class="card-header">
                                <b>Id: </b>{{ $group->id }}
                                <br> <b>Group name: </b> {{ $group->group_name }}
                            </div>
                            <div class="card-body">
                                <li><a href="/device_groups/{{$group->id}}">show</a></li>
                                {{--<li><a href="/device_groups/{{$group->id}}/edit">edit</a></li>--}}
                                <editgroup group_name="{{$group->group_name }}" group_id="{{$group->id}}"></editgroup>
                            </div>
                            </div>
                         @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
