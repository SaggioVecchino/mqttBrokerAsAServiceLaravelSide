@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/projects/{{$group->project_id}}" class="btn">Go back!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                <b>Id: </b>{{$group->id}}<br>
                <b>Group name: </b> {{$group->group_name}}
                </div>
                <div class="card-header"><b>Devices:</b></div>
                @if(count($devices))
                @foreach($devices as $device)
                <div class="card-body">
                    {{$device->device_name}}
                </div>
                @endforeach
                @else
                    <div class="card-body">
                        No device attached to this group
                    </div>
                @endif
            </div>
            <br>
            <div class="card">
                <div class="card-header"><b>Permissions on publications:</b>
                <br>
                <a href="/device_groups_topics/create?type=publication&amp;allow=1&amp;group_id={{$group->id}}">+add</a></div>
                <div class="card-body">
                @if(count($permissionsPublications))
                    <ul>
                    @foreach($permissionsPublications as $element)
                        <li>{{$element->topic_name}}</li>
                    @endforeach
                    </ul>
                @else
                    No permission on publications attached to this group
                @endif
                </div>


                <div class="card-header"><b>Prohibitions on publications:</b>
                <br>
                <a href="/device_groups_topics/create?type=publication&amp;allow=0&amp;group_id={{$group->id}}">+add</a></div>
                <div class="card-body">
                @if(count($prohibitionsPublications))
                    <ul>
                    @foreach($prohibitionsPublications as $element)
                        <li>{{$element->topic_name}}</li>
                    @endforeach
                    </ul>
                @else
                    No prohibition on publications attached to this group
                @endif
                </div>


                <div class="card-header"><b>Permissions on subscribtions:</b>
                <br>
                <a href="/device_groups_topics/create?type=subscribtion&amp;allow=1&amp;group_id={{$group->id}}">+add</a></div>
                <div class="card-body">
                @if(count($permissionsSubscribtions))
                    <ul>
                    @foreach($permissionsSubscribtions as $element)
                        <li>{{$element->topic_name}}</li>
                    @endforeach
                    </ul>
                @else
                    No permission on subscribtions attached to this group
                @endif
                </div>


                <div class="card-header"><b>Prohibitions on subscribtions:</b>
                <br>
                <a href="/device_groups_topics/create?type=subscribtion&amp;allow=0&amp;group_id={{$group->id}}">+add</a></div>
                <div class="card-body">
                @if(count($prohibitionsSubscribtions))
                    <ul>
                    @foreach($prohibitionsSubscribtions as $element)
                        <li>{{$element->topic_name}}</li>
                    @endforeach
                    </ul>
                @else
                    No prohibition on subscribtions attached to this group
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
