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
                    
                    <div id="requestSets">
                        <div id="requestSetsContainer">
                            <div id="requestSet_0">
                                <h1>Set 1:</h1><br>
                                <div class="topicsContainer">
                                <div class="topics">
                                        {{ Form::text('requestSets[0][topics][]','',array('placeholder' => 'topic_name')) }}
                                    </div>
                                    <button class="addTopic" type="button" onclick="addTopic(0)">Add a topic</button>
                                </div>
                                <br><br>
                                <div class="devices_container">
                                    <div class="devices">
                                        <div class="device_0">
                                            <button class="specifyGroupAndDevice" type="button" onclick="addGroupName(0)">Not all project devices</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <button class="addRequestSet" onclick="addRequestSet()" type="button">Add a set</button>
                        <hr>
                    </div>
                    
                    <br><br>

                    <b>Interval :</b>{{Form::select('interval', array('Y' => 'Year', 'M' => 'Month',
                        'W' => 'Week', 'D' => 'Day', 'H' => 'Hour'))}}
                    <br>
                    <b>Frequence: </b>{{Form::select('freq', array('M' => 'Month',
                        'W' => 'Week', 'D' => 'Day', 'H' => 'Hour', 'Mn' => 'Minute'))}}
                    <br>
                    <b>Aggregate: </b>{{Form::select('agg', array(
                        'avg' => 'avg',
                        'max' => 'max',
                        'count' => 'count',
                        'min' => 'min',
                        'sum' => 'sum'))}}
                    <br>
                    <b>Type of graph: </b>{{Form::select('type', array(
                        'line' => 'Line',
                        'bar' => 'Bar'
                        ))}}
                    {{ Form::submit('OK') }}
                    {{ Form::close() }} 
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var nbSets = 0;
    
    var nbDevicesInSet = [0];

    function createInput(type, placeholder, name){
        var input = document.createElement('input');
        var type_ = document.createAttribute('type');
        type_.value = type;
        var placeholder_ = document.createAttribute('placeholder');
        placeholder_.value = placeholder;
        var name_ = document.createAttribute('name');
        name_.value = name;
        input.setAttributeNode(type_);
        input.setAttributeNode(placeholder_);
        input.setAttributeNode(name_);
        return input;
    }

    function createTextInput(placeholder, name){
        return createInput('text', placeholder, name);
    }

    function createButton(className, onclick, inner){
        var button = document.createElement('button');
        var class_ = document.createAttribute('class');
        class_.value = className;
        var onclick_ = document.createAttribute('onclick');
        onclick_.value = onclick;
        var type_ = document.createAttribute('type');
        type_.value = 'button';
        button.setAttributeNode(class_);
        button.setAttributeNode(onclick_);
        button.setAttributeNode(type_);
        button.innerHTML = inner;
        return button;
    }

    function createDiv(className){
        var div = document.createElement('div');
        var class_ = document.createAttribute('class');
        class_.value = className;
        div.setAttributeNode(class_);
        return div;
    }

    function addInnerHTML(container,content){
        container.insertAdjacentHTML('beforeend',content);
    }

    function replaceInnerHTML(container,content){
        container.innerHTML = content;
    }

    function addTopic(set) {
        var field = document.querySelector('#requestSet_'.concat(set,' .topics'));
        field.appendChild(createTextInput('topic_name','requestSets['.concat(set,'][topics][]')));
    }

    function addSpecifiedDeviceName(set, device) {
        var field = document.querySelector('#requestSet_'.concat(set,' .device_',device));
        field.appendChild(createTextInput('device_name (empty for all)',
        'requestSets['.concat(set,'][devices][',device,'][device_name]')));
        var button = document.querySelector('#requestSet_'.concat(set, ' .device_', device, ' .specifyDevice'));
        button.remove();
    }

    function addDevice(set) {
        var devicesDiv = document.querySelector('#requestSet_'.concat(set, ' .devices'))
        var groupInput = createTextInput('group_name','requestSets['.concat(set,'][devices][',nbDevicesInSet[set],'][group_name]'));
        var buttonSpecifyDevice = createButton('specifyDevice','addSpecifiedDeviceName('.concat(set,',',nbDevicesInSet[set],')'),'Specify device');
        var div = createDiv('device_'.concat(nbDevicesInSet[set]));
        
        div.appendChild(groupInput);
        div.appendChild(buttonSpecifyDevice);
        devicesDiv.appendChild(div);
        nbDevicesInSet[set]++;
    }

    function addGroupName(set){
        var devices = document.querySelector('#requestSet_'.concat(set,' .device_0'));
        var content = '<input placeholder="group_name" name="requestSets['.concat(set,'][devices][0][group_name]" value="" type="text"><button class="specifyDevice" type="button" onclick="addSpecifiedDeviceName(',set,',0)">Specify device</button>');
        replaceInnerHTML(devices,content);
        var container = document.querySelector('#requestSet_'.concat(set,' .devices_container'));
        content = '<button class="addDevices" type="button" onclick="addDevice('.concat(set,')">Add devices</button>');
        addInnerHTML(container,content);
        nbDevicesInSet[set]++;
    }

    function addRequestSet(){
        var requestSetsContainer = document.querySelector('#requestSetsContainer');
        nbSets++;
        toAdd = '<div id="requestSet_'.concat(nbSets,'"><h1>Set ',nbSets+1,':</h1><br><div class="topicsContainer"><div class="topics"><input placeholder="topic_name"name="requestSets[',nbSets,'][topics][]" value="" type="text"></div><button class="addTopic" type="button" onclick="addTopic(',nbSets,')">Add a topic</button></div><br><br><div class="devices_container"><div class="devices"><div class="device_0"><button class="specifyGroupAndDevice" type="button" onclick="addGroupName(',nbSets,')">Not all project devices</button></div></div></div></div><hr>');
        addInnerHTML(requestSetsContainer,toAdd);
        nbDevicesInSet.push(0);
    }

</script>
@endsection
