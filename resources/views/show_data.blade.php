@extends('layouts.app')
@section('content')
<div class="container">
    <a href="/projects/$project_id/specify_data" class="btn">Go back!</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
                @endif -->
            <div class="card">
                <div class="card-header">
                    <b>Data for:</b> <br>
                    <!-- we can specify the type of data the user asked -->
                </div>

                <div class="ct-chart ct-perfect-fourth">

                </div>
                <!-- <canvas id="myChart" width="400" height="400"></canvas> -->
                

                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/2.10.6/moment-with-locales.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/2.10.6/locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.4/moment-timezone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/locale/fr.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>

<script src="moment.js"></script>

<script>
    var response = @json($response);
    var type = @json($type);
    var interval = @json($interval);
    var freq = @json($freq);
    var series = [];
    for(var i = 0 ; i < response.length ; i++){
        var data = response[i].map(o=>{return {x: new Date(o.x), y: o.y}})

        data.sort(function(a,b){
            return a.x - b.x;
        });

        var obj = {name: 'series-'.concat(i) , data: data};
        series[i] = obj;
    } 
    
    var axisX = function(format){
        return ({
                    axisX: {
                        type: Chartist.FixedScaleAxis,
                        divisor: 5,
                        labelInterpolationFnc: function(value) {
                            return moment(value).format(format);
                        }
                    }
                });
    }

    var getFormat = function(interval, freq){
        //using interval and freq and return a string date format according to moment js
        return 'MMM D';
    }

    var traceGraph = function(type, format){
        switch(type){
            moment().format();
            case 'line':
                new Chartist.Line('.ct-chart', {
                    series: series
                },{
                    axisX: axisX(format);
                });
                break;
            case 'bar':
                new Chartist.Bar('.ct-chart', {
                        series: series
                    },{
                        axisX: axisX(format);
                    });
                    break;
        }
    }

    window.addEventListener('load', function() {
        traceGraph(type,getFormat(interval, freq));
    }
</script>