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
                <div class="card-header">
                    Permissions and prohibitions
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header">
                            <b>Permissions on publications:</b>
                            <br>
                            <permission group_id="{{$group->id}}" type="publication" allow="1" modalid="publication1">
                            </permission>
                        </div>
                        <div class="card-body">
                            @if(count($permissionsPublications))
                            <ul>
                                @foreach($permissionsPublications as $element)
                                <li>{{$element->topic_name}}</li>
                                @endforeach
                            </ul>
                            @else No permission on publications attached to this group @endif
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            <b>Prohibitions on publications:</b>
                            <br>
                            <permission group_id="{{$group->id}}" type="publication" allow="0" modalid="publication0">
                            </permission>
                        </div>
                        <div class="card-body">
                            @if(count($prohibitionsPublications))
                            <ul>
                                @foreach($prohibitionsPublications as $element)
                                <li>{{$element->topic_name}}</li>
                                @endforeach
                            </ul>
                            @else No prohibition on publications attached to this group @endif
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            <b>Permissions on subscribtions:</b>
                            <br>
                            <permission group_id="{{$group->id}}" type="subscribtion" allow="1" modalid="subscribtion1">
                            </permission>
                        </div>
                        <div class="card-body">
                            @if(count($permissionsSubscribtions))
                            <ul>
                                @foreach($permissionsSubscribtions as $element)
                                <li>{{$element->topic_name}}</li>
                                @endforeach
                            </ul>
                            @else No permission on subscribtions attached to this group @endif
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            <b>Prohibitions on subscribtions:</b>
                            <br>
                            <permission group_id="{{$group->id}}" type="subscribtion" allow="0" modalid="subscribtion0">
                            </permission>
                        </div>
                        <div class="card-body">
                            @if(count($prohibitionsSubscribtions))
                            <ul>
                                @foreach($prohibitionsSubscribtions as $element)
                                <li>{{$element->topic_name}}</li>
                                @endforeach
                            </ul>
                            @else No prohibition on subscribtions attached to this group @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection