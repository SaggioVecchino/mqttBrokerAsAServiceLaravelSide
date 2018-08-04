@extends('layouts.app')
@section('content')
<div class="container">
    <a href="/projects" class="btn btn-outline-danger">Go back!</a>
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

                <!-- <div class="ct-chart ct-perfect-fourth"></div> -->
                <canvas id="myChart"></canvas>
                

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>

<script>
const responseTmp = @json($response);
const response = responseTmp.series;
const agg = responseTmp.agg;
const requestSets = responseTmp.requestSets;
const type = @json($type);
const interval = @json($interval);
const freq = @json($freq);
const projectId = @json($project_id);
const defaultFormat = 'MMM D YYYY h:mm a'
var format = defaultFormat;
var config = {};
var updateInterval = 0;

var getSeries = function (response, labels) {
    var series = [];
    for (var i = 0; i < response.length; i++) {
        var data = response[i].map(o => { return { x: new Date(o.x), y: o.y } });
        var dataSetColor = getRandomColor();
        data.sort(function (a, b) {
            return a.x - b.x;
        });

        series[i] = {
            label: labels[i],
            data: data,
            fill: false,
            backgroundColor: dataSetColor,
            borderColor: dataSetColor
        };
    }
    return series;
}

var getFormat = function (interval, freq) {
    //using interval and freq and return a string date format according to moment js
    switch (interval) {
        case 'Y':
            switch (freq) {
                case 'M':
                    return 'MMM';
                case 'W':
                    return 'll';
                case 'D':
                    return 'MMM D';
                case 'H':
                    return 'MMM D hA';
                case 'Mn':
                    return 'MMM D h:mm a';
            }
        case 'M':
            switch (freq) {
                case 'W':
                    return 'll';
                case 'D':
                    return 'MMM D';
                case 'H':
                    return 'MMM D hA';
                case 'Mn':
                    return 'MMM D h:mm a';
            }
        case 'W':
            switch (freq) {
                case 'D':
                    return 'MMM D';
                case 'H':
                    return 'MMM D hA';
                case 'Mn':
                    return 'MMM D h:mm a';
            }
        case 'D':
            switch (freq) {
                case 'H':
                    return 'hA';
                case 'Mn':
                    return 'h:mm a';
            }
        case 'H':
            switch (freq) {
                case 'Mn':
                    return 'h:mm a';
            }
    }
    return defaultFormat;
}

/* var traceGraphChartist = function (series, type, format) {
    switch (type) {
        case 'line':
            new Chartist.Line('.ct-chart', {
                series: series
            }, {
                    axisX: {
                        type: Chartist.FixedScaleAxis,
                        divisor: 10,
                        labelInterpolationFnc: function (value) {
                            return moment(value).format(format);
                        }
                    }

                });
            break;
        case 'bar':
            new Chartist.Bar('.ct-chart', {
                series: series
            }, {
                    axisX: {
                        type: Chartist.FixedScaleAxis,
                        divisor: 10,
                        labelInterpolationFnc: function (value) {
                            return moment(value).format(format);
                        }
                    }
                });
            break;
    }
} */

var getRandomColor = function () {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

var removeDuplicates = function (arr) {
    o = {}
    arr.forEach(function (e) {
        o[e] = true
    })
    return Object.keys(o)
}

var getLabels = function (series) {
    labels = [];
    series.forEach(function (serie) {
        serie.data.forEach(function (element) {
            labels.push(element.x);
        })
    })
    return removeDuplicates(labels);
}

var traceGraphChart = function (series, type, format) {

    config = {
        type: type,
        data: {
            datasets: series
        },
        options: {
				responsive: true,
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
                scales: {
                xAxes: [{
                    display: true,
                    type: 'time',
                    time: {
                        format: format,
                        tooltipFormat: format
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'value'
                    }
                }]
            },
		}
    };

    var ctx = document.getElementById('myChart').getContext('2d');

    window.myLine = new Chart(ctx, config);
}

var serieContainsDate = function (serie, date) {
    var len = serie.length;
    for (var i = 0; i < len; i++) {
        if (serie[i].x.getTime() == (new Date(date)).getTime())
            return i;
    }
    return false;
}

var updateSeries = function (oldSeries, newSeries) {
    var len = oldSeries.length;
    if (len !== newSeries.length)
        return;
    for (var i = 0; i < len; i++) {
        newSeries[i].forEach(function (e) {
            var k = serieContainsDate(oldSeries[i].data, e.x);
            // console.log(new Date(e.x) === new Date(oldSeries[i].data));

            if (k !== false)
                oldSeries[i].data[k].y = e.y;
            else{
                oldSeries[i].data.push({x: new Date(e.x), y: e.y});
            }
        })
        oldSeries[i].data.sort(function (a, b) {
            return a.x - b.x;
        });
    }
}

var updateGraphChart = function (series) {
    $.post(`http://iot2.brainiac.dz/projects/${projectId}/update_data`,
        {
            type: type,
            freq: freq,
            interval: freq,// interval == freq
            requestSets: requestSets,
            agg: agg
        },
        function (response, status) {
            updateSeries(series, response);
            config.data.datasets = series;
            window.myLine.update();
        }).fail(function(){
            console.log("hahha");
            clearInterval(updateInterval);
        });
}

window.addEventListener('load', function () {
    var labels = requestSets.map(o=>o.label)
    var series = getSeries(response, labels);
    format = getFormat(interval, freq);
    traceGraphChart(series, type, format);
    updateInterval = setInterval(function() {updateGraphChart(series);} , 1500);
});

</script>