@extends('layouts.app') @section('content')
<div class="container">
    <a href="/projects/{{$group->project_id}}" class="btn">Go back!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <b>Id: </b>{{$group->id}}
                    <br>
                    <b>Group name: </b> {{$group->group_name}}
                </div>
                <div class="card-header">
                    <b>Devices:</b>
                </div>
                <div class="card-body">
                    @if(count($devices))
                    <ul>
                        @foreach($devices as $device)
                        <li>{{$device->device_name}}</li>
                        @endforeach
                    </ul>
                    @else
                    <div class="card-body">
                        No device attached to this group
                    </div>
                    @endif
                </div>
            </div>
            <br>
            <div class="card">

                <permission title="Permissions on publications:" type="publication" allow="1"
                            project_id="{{$group->project_id}}" group_id="{{$group->id}}"
                            :permissions="{{$permissionsPublications->toJson()}}"  modalid="publication1"></permission>

                <permission title="Prohibitions on publications:" type="publication" allow="0"
                            project_id="{{$group->project_id}}"  group_id="{{$group->id}}"
                            :permissions="{{$prohibitionsPublications->toJson()}}"  modalid="publication0"></permission>

                <permission title="Permissions on subscribtions:" type="subscribtion" allow="1"
                            project_id="{{$group->project_id}}"  group_id="{{$group->id}}"
                            :permissions="{{$permissionsSubscribtions->toJson()}}"  modalid="subscribtion1"></permission>

                <permission title="Prohibitions on subscribtions:" type="subscribtion" allow="0"
                            project_id="{{$group->project_id}}"  group_id="{{$group->id}}"
                            :permissions="{{$prohibitionsSubscribtions->toJson()}}"  modalid="subscribtion0"></permission>

        </div>
    </div>
    </div>

</div>

@endsection
