<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token"  content="{{ csrf_token() }}"  >
        <title>Horizontal support</title>

        <style type="text/css">

        </style>
    </head>
    <body>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/indicators/indicators.js"></script>
<script src="https://code.highcharts.com/stock/indicators/ema.js"></script>

<script src="https://code.highcharts.com/highcharts-more.js"></script>

<!-- <script src="https://code.highcharts.com/stock/indicators/rsi.src.js"></script> -->
<!-- <script src="{{asset('highchart/code/indicators/rsi.src.js')}}"></script> -->

<div id='testlogo'>
    <img src="{{asset('logo-png.png')}}">
</div>
<div id="container" style="height: 800px; min-width: 310px"></div>
<input id="symbol" type="hidden" value="{{$symbol}}">
<input id="interval" type="hidden" value="{{$interval}}">


<script type="text/javascript">

    $(document).ready(function(){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        var symbol=$('#symbol').val();
        var interval=$('#interval').val();

        // AJAX request
        $.ajax({
            url: '{{ route("CandleSticks") }}',
            data :{symbol:symbol,interval:interval},
            type: 'post',
            success: function(datas) {
                data=datas[0];
                points=datas[1];
                volume=datas[2];
                Highcharts.stockChart('container', {

                    chart: {
                        backgroundColor: '#171a2d',
                        /*{ linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                        stops:
                        [[0, '#2a2a2b'],[1, '#3e3e40']] }*/
                        style: {
                            fontFamily: '\'Unica One\', sans-serif'
                        },
                        plotBorderColor: '#606063',
                    },


                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.0)',
                        positioner: function ()  {
                            return { x: 400, y: 20 };
                        },
                        shadow: false,
                        borderWidth: 0,
                        useHTML: true,
                    },

                    plotOptions:{
                        line:  {
                            dataLabels: {
                                enabled: true
                            }
                        },
                        series: {
                            dataLabels: {
                                color: '#B0B0B3'
                            },
                            marker: {
                                lineColor: '#333'
                            }
                        },
                        boxplot: {
                            fillColor: '#505053'
                        },
                        candlestick: {
                            color: '#4c83e7',
                            upColor: '#00ff00',
                            lineColor: 'white'
                        },
                        errorbar: {
                            color: 'white'
                        }
                    },
                    legend: {
                        itemStyle: {
                            color: '#E0E0E3'
                        },
                        itemHoverStyle: {
                                color: '#FFF'
                        },
                        itemHiddenStyle: {
                            color: '#606063'
                        }
                    },
                    credits:  {
                        style:  {
                            color: '#fff'
                        }
                    },
                    labels: {
                        style:  {
                            color: '#707073'
                        }
                    },
                    navigation: {
                        buttonOptions: {
                            symbolStroke: '#DDDDDD',
                            theme: {
                                fill: '#505053'
                            }
                        }
                    },
                    // scroll charts
                    rangeSelector:
                    {
                        buttonTheme:
                        {
                            fill: '#505053',
                            stroke: '#000000',
                            style:
                            {
                                color: '#CCC'
                            },
                            states:
                            {
                                hover:
                                {
                                    fill: '#707073',
                                    stroke: '#000000',
                                    style:  {
                                        color: 'white'
                                    }
                                },
                                select:
                                {
                                    fill: '#000003',
                                    stroke: '#000000',
                                    style: {
                                        color: 'white'
                                    }
                                }
                            }
                        },
                        inputBoxBorderColor: '#505053',
                        inputStyle:
                        {
                            backgroundColor: '#333',
                            color: 'silver'
                        },
                        labelStyle:
                        {
                            color: 'silver'
                        }
                    },
                    navigator: {
                        handles: {
                            backgroundColor: '#666',
                            borderColor: '#AAA'
                        },
                        outlineColor: '#CCC',
                        maskFill: 'rgba(255,255,255,0.1)',
                        series: {
                            color: '#7798BF',
                            lineColor: '#A6C7ED'
                        }
                    },
                    scrollbar: {
                        barBackgroundColor: '#808083',
                        barBorderColor: '#808083',
                        buttonArrowColor: '#CCC',
                        buttonBackgroundColor: '#606063',
                        buttonBorderColor: '#606063',
                        rifleColor: '#FFF',
                        trackBackgroundColor: '#404043',
                        trackBorderColor: '#404043'
                    },

                    rangeSelector: {
                        selected: 2
                    },
                    rangeSelector: {
                        enabled: false
                    },
                    navigator:  {
                        enabled: false
                    },

                    xAxis: {
                        gridLineColor: 'rgba(255,255,255,0.1)',
                        labels: {
                            style: {
                                color: 'white'
                            }
                        },
                    },

                    yAxis: [{
                            height: '75%',
                            gridLineColor: 'rgba(255,255,255,0.1)',
                            resize: {
                                enabled: false
                            },
                            labels: {
                                align: 'right',
                                x: -3,
                                style: {
                                    color: 'white'
                                }
                            },
                            title: {
                                text: symbol
                            },
                            plotLines: [{
                                color: '#FF0000',
                                width: 2,
                                value: 5.5
                            }],
                        },
                        {
                            top: '75%',
                            height: '25%',
                            labels: {
                                align: 'right',
                                x: -3
                            },
                            offset: 0,
                            title: {
                                text: 'Volume'
                            }
                        }
                    ],

                    title: {
                        text: '.Horizontal Support Level'
                    },
                    subtitle: {
                        text: symbol+' - .Horizontal Support Level - '+interval
                    },
                    series: [ {
                                type: 'candlestick',
                                id: symbol,
                                name: symbol+' Stock Price',
                                data: data,
                                zIndex: 1
                            },

                            {
                                type: 'polygon',
                                color: '#ffffff78',
                                data: [
                                    [parseFloat(points.time_1), parseFloat(points.low_1)],
                                    [parseFloat(points.time_2), parseFloat(points.low_2)],
                                    [parseFloat(points.time_3), parseFloat(points.low_3)],
                                    [parseFloat(points.time_4), parseFloat(points.low_4)],
                                ],
                                enableMouseTracking: false
                            },
                            {
                                type: 'column',
                                id: 'volume',
                                yAxis: 1,
                                data:volume,
                            }
                    ]
                });
            }
        });
    });

    Highcharts.seriesTypes.column.prototype.pointAttribs = (function(func) {
        return function(point, state) {
        var attribs = func.apply(this, arguments);
        
        var candleSeries = this.chart.series[0]; // Probably you'll need to change the index
        var candlePoint = candleSeries.points.filter(function(p) { return p.index == point.index; })[0];

        var color = (candlePoint.open < candlePoint.close) ? '#51e2958a' : '#f96777a3'; // Replace with your colors
        attribs.fill = state == 'hover' ? Highcharts.Color(color).brighten(0.3).get() : color;
        
        return attribs;
        };
    }(Highcharts.seriesTypes.column.prototype.pointAttribs));
</script>

<style>

tspan{
    color: #FFF !important;
    fill: #FFF  !important;
}

#testlogo {
    top: 50%;
    z-index: 100;
    position: absolute;
    width: 350px;
    left: 50%;
    margin-left: -175px;
    height: 70px;
    margin-top: -35px;
    opacity: .5;
}

 #testlogo img{
    width:100%;
}

.highcharts-color-0 span b, .highcharts-color-0 span  br{
    float:left !important;
}

.highcharts-contextbutton{
    visibility: hidden;
}

.highcharts-color-0 span  br{
    float:left !important;
    display: none !important;
    margin-right: 2px !important;
}

.highcharts-title tspan{
    font-size: 22px !important;
    display: block;
    margin-top: 10px;
}

.highcharts-subtitle tspan{
    font-size: 22px !important;
    display: block;
    margin-top: 10px;
}

.highcharts-tooltip .highcharts-label {
    position:relative !important;
    display:inline-block !important;
    float:left  !important;
    left:0px !important;
    top:0px !important;
}

.highcharts-tooltip {
    position: absolute;
    left: 50% !important;
    top: 70px !important;
    opacity: 1;
    margin-left: -250px;
    width: 500px;
    display: inline-block;
    height: 32px;
}

.highcharts-label span{
    color:#FFF !important;
}

.highcharts-tooltip .highcharts-color-none:first-child{
    left:-140px !important;
}

.highcharts-tooltip .highcharts-color-none {
    left:500px !important;
}

path.highcharts-point.highcharts-point-down {
    fill: #ff5869 !important;
    stroke: #ff5869 !important;
}

path.highcharts-point.highcharts-point-up {
    fill: #05c481 !important;
    stroke: #05c481 !important;
}

rect.highcharts-plot-background {
    strtok: #ffffff !important;
}

g.highcharts-grid.highcharts-xaxis-grid {
    stroke: rgba(255,255,255,0.1)  !important;
}

/* path.highcharts-graph {
    stroke: #be29ec !important;
} */

path.highcharts-crosshair.highcharts-crosshair-thin.undefined {
    stroke: #ffffff !important;
}

.highcharts-tooltip .highcharts-label:nth-child(3) {
    left: 650px !important;
}

g.highcharts-grid.highcharts-yaxis-grid path.highcharts-grid-line {
    stroke: rgba(255,255,255,0.1) !important;
}

@media only screen and (max-width: 1024px) {
    .highcharts-tooltip .highcharts-color-none:first-child {
        left: -140px !important;
    }
}

@media only screen and (max-width: 768px) {

    .highcharts-tooltip .highcharts-color-none:first-child {
        left: 50% !important;
        position: absolute !important;
        top: -19px !important;
        margin-left: -49px;
    }

    .highcharts-tooltip .highcharts-label {
        text-align: center;
        width: 100%;
    }

    .highcharts-tooltip .highcharts-color-none:last-child {
        left: 50% !important;
        position: absolute !important;
        top: 19px !important;
        margin-left: -49px;
    }

    .highcharts-color-0 span {
    width: 100%;
    position: relative !important;
    white-space: normal !important;
    word-break: break-word;
    }

    .highcharts-color-0 span span{
        display:none !important;
    }

    .highcharts-color-0 span b, .highcharts-color-0 span br {
        float: none !important;
    }

    .highcharts-tooltip {
        position: absolute;
        left: 0% !important;
        top: 70px !important;
        opacity: 1;
        margin-left: 0px;
        width: 100%;
        display: inline-block;
        height: 32px;
        padding:0px 5px;
    }

}

@media only screen and (max-width: 768px) {
    .highcharts-tooltip {
        position: absolute;
        left: 0% !important;
        top: 56px !important;
        opacity: 1;
        margin-left: 0px;
        width: 100%;
        display: inline-block;
        height: 65px;
        padding: 0px 5px;
            background: rgba(49, 49, 50, 0.9294117647058824);
    }
    .highcharts-tooltip .highcharts-color-none:last-child {
        left: 50% !important;
        position: absolute !important;
        top: auto !important;
        margin-left: -49px;
        bottom: 27px;
    }
    .highcharts-tooltip .highcharts-color-none:first-child {
        left: 50% !important;
        position: absolute !important;
        top: -14px !important;
        margin-left: -68px;
    }
    .highcharts-tooltip .highcharts-color-none:last-child {
        left: 50% !important;
        position: absolute !important;
        top: auto !important;
        margin-left: -58px;
        bottom: 27px;
    }
}


        </style>
    </body>
</html>
