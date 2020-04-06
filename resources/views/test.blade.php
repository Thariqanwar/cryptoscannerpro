<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token"  content="{{ csrf_token() }}"  >
        <!-- <meta http-equiv="refresh" content="30"/> -->
		<title>Highstock Example</title>

		<style type="text/css">
      #testlogo { z-index: 100; }
		</style>
	</head>
	<body>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/indicators/indicators.js"></script>
<script src="https://code.highcharts.com/stock/indicators/ema.js"></script>

<script src="{{asset('highchart/code/indicators/rsi.src.js')}}"></script>


  <div id='testlogo'>
      <img src="{{asset('logo.png')}}">
  </div>
<div id="container" style="height: 800px; min-width: 310px">
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        symbol='CHATBTC';
          // AJAX request
           /* var symbol=$('#symbol').val();*/
          $.ajax({
           url: '{{route('CandleSticks')}}',
          data :{symbol:symbol,interval:'1h'},
           type: 'post',
          
           success: function(datas)
                       { 
                           data=datas[0];

                           points=datas[1];
                            //console.log(points);
                          
                           Highcharts.stockChart('container', {
                                
                           chart:  
                           {
                               backgroundColor:'black', 
                               /*{
                                   linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                                   stops: 
                                   [[0, '#2a2a2b'],[1, '#3e3e40']]
                               },*/
                               style: 
                               {
                                   fontFamily: '\'Unica One\', sans-serif'
                               },
                               plotBorderColor: '#606063'
                           },
                        tooltip: 
                           {
                               backgroundColor: 'rgba(0, 0, 0, 0.0)',
                               positioner: function () 
                               {
                                   return { x: 400, y: 20 };
                               },
                               shadow: false,
                               borderWidth: 0,
                               useHTML: true,
                           },            
                           plotOptions: 
                           {
                               line: 
                               {
                                   dataLabels: 
                                   {
                                       enabled: true
                                   }
                               },
                               series: 
                               {
                                   dataLabels: 
                                   {
                                       color: '#B0B0B3' 
                                   },
                                   marker: 
                                   {
                                       lineColor: '#333'
                                   }
                               },
                               boxplot: 
                               {
                                   fillColor: /*'#505053'*/ '#FF0000'
                               },
                               candlestick: 
                               {

                                    color: '#00ff00',
                                    upColor: '#4c83e7',
                                      
                                    lineColor: 'white'
                               },
                               errorbar: 
                               {
                                   color: 'white'
                               }
                           },
                           legend: 
                           {
                               itemStyle: 
                               {
                                   color: '#E0E0E3'
                               },
                               itemHoverStyle: 
                               {
                                       color: '#FFF'
                               },
                               itemHiddenStyle: 
                               {
                                   color: '#606063'
                               }
                           },
                           credits: 
                           {
                               style: 
                               {
                                   color: '#666'
                               }
                           },
                           labels: 
                           {
                               style: 
                               {
                                   color: '#707073'
                               }
                           },
                           navigation: 
                           {
                               buttonOptions: 
                               {
                                   symbolStroke: '#DDDDDD',
                                   theme: 
                                   {
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
                                           style: 
                                           {
                                               color: 'white'
                                           }
                                       },
                                       select: 
                                       {
                                           fill: '#000003',
                                           stroke: '#000000',
                                           style: 
                                           {
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
                               },
                               xAxis: {
                                   gridLineColor: '#505053'
                               }
                           },
                           scrollbar: 
                           {
                               barBackgroundColor: '#808083',
                               barBorderColor: '#808083',
                               buttonArrowColor: '#CCC',
                               buttonBackgroundColor: '#606063',
                               buttonBorderColor: '#606063',
                               rifleColor: '#FFF',
                               trackBackgroundColor: '#404043',
                               trackBorderColor: '#404043'
                           },

                           rangeSelector: 
                           {
                               selected: 2
                           },
                           rangeSelector: 
                           {
                               enabled: false
                           },
                           navigator: 
                           {
                               enabled: false
                           },

                           yAxis: 
                           [   {
                                   height: '75%',
                                   resize: {
                                               enabled: false
                                           },
                                   labels: {
                                               align: 'right',
                                               x: -3
                                           },
                                   title:  {
                                               text: symbol
                                           },
                               }, 
                               {
                                   top: '75%',
                                   height: '25%',
                                   labels: 
                                   {
                                       align: 'right',
                                        x: -3
                                   },
                                   offset: 0,
                                   title: 
                                   {
                                       text: 'RSI'
                                   }
                               }
                           ],

                           title: 
                           { 
                               text: symbol+' Stock Price' 
                           },
                           subtitle: 
                           { 
                               text: 'With RSI and Pivot Points technical indicators'
                           },
                           series: [
                                       {
                                           type: 'candlestick',
                                           id: symbol,
                                           name: symbol+' Stock Price',
                                           data: data,
                                           zIndex: 1
                                       }, 
                                       {
                                           type: 'rsi',
                                           id:  'rsi',
                                           color: '#FFF',
                                           yAxis: 1,
                                           linkedTo: symbol,
                                           marker: {
                                                       enabled: false
                                                   }
                                       },
                                      /* {
                                           type: 'line',
										                      color: '#ffe37d',
										   
										   
                                           data:[  
                                                    [data[60][0],10100],
                                                    [data[68][0],10080],
                                                    [data[73][0],10050]
                                                  
                                               ]
                                       },
                                       {
                                          type: 'line',
                                          yAxis: 1,
										                      color: '#ff4800',
                                          data:[  
                                                    [data[60][0],parseFloat(parseFloat(Math.round(40.655465 * 100) / 100).toFixed(2))],
                                                    [data[68][0],50],
                                                    [data[73][0],55] 
                                                   
                                               ]
                                       }*/
                                   ]
                           });
                       }
         });
    });    
           
</script>   
<script type="text/javascript">

		


function getLinearRegression(xData, yData) {

    var sumX = 0,
        sumY = 0,
        sumXY = 0,
        sumX2 = 0,
        linearData = [],
        linearXData = [],
        linearYData = [],
        n = xData.length,
        alpha, beta, i, x, y;

    // Get sums:
    for (i = 0; i < n; i++) {
        x = xData[i];
        y = yData[i];
        sumX += x;
        sumY += y;
        sumXY += x * y;
        sumX2 += x * x;
    }

    // Get slope and offset:
    alpha = (n * sumXY - sumX * sumY) / (n * sumX2 - sumX * sumX);
    if (isNaN(alpha)) {
        alpha = 0;
    }
    beta = (sumY - alpha * sumX) / n;

    // Calculate linear regression:
    for (i = 0; i < n; i++) {
        x = xData[i];
        y = alpha * x + beta;

        // Prepare arrays required for getValues() method
        linearData[i] = [x+3, y+3];
        linearXData[i] = x+3;
        linearYData[i] = y+3;
    }
    console.log(linearData);
    return {
        xData: linearXData,
        yData: linearYData,
        values: linearData
    };
}



Highcharts.seriesType(
    'linearregression',
    'rsi', {
        name: 'Linear Regression',
        enableMouseTracking: true,
        marker: {
            enabled: false
        },
        params: {} // linear regression doesn’t need params
    }, {
        getValues: function (series) {
            return this.getLinearRegression(series.xData, series.yData);
        },
        getLinearRegression: getLinearRegression
    }
);
datas= [
            
            [
                1559741400000,
                184.28,
                184.99,
                181.14,
                182.54
            ],
            [
                1559827800000,
                183.08,
                185.47,
                182.15,
                185.22
            ],
            [
                1559914200000,
                186.51,
                191.92,
                185.77,
                190.15
            ],
            [
                1560173400000,
                191.81,
                195.37,
                191.62,
                192.58
            ],
            [
                1560259800000,
                194.86,
                196,
                193.6,
                194.81
            ],
            [
                1560346200000,
                193.95,
                195.97,
                193.39,
                194.19
            ],
            [
                1560432600000,
                194.7,
                196.79,
                193.6,
                194.15
            ],
            [
                1560519000000,
                191.55,
                193.59,
                190.3,
                192.74
            ],
            [
                1560778200000,
                192.9,
                194.96,
                192.17,
                193.89
            ],
            [
                1560864600000,
                196.05,
                200.29,
                195.21,
                198.45
            ],
            [
                1560951000000,
                199.68,
                199.88,
                197.31,
                197.87
            ],
            [
                1561037400000,
                200.37,
                200.61,
                198.03,
                199.46
            ],
            [
                1561123800000,
                198.8,
                200.85,
                198.15,
                198.78
            ],
            [
                1561383000000,
                198.54,
                200.16,
                198.17,
                198.58
            ]
        ];


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
        width: 300px;
        left: 45%;
        margin-left: -75px;
        height: 150px;
        margin-top: -75px;
        opacity: .5;
    }
    #testlogo img{
      width:100%;
      }
    .highcharts-color-0 span b, .highcharts-color-0 span  br{
      float:left !important;
      }
     .highcharts-color-0 span  br{
      float:left !important;
      display: none !important;
        margin-right: 2px !important;
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
    .highcharts-label span{
      color:#FFF !important;
      }
    .highcharts-tooltip .highcharts-color-none:first-child{
      left:-140px !important;
      } 
    .highcharts-tooltip .highcharts-color-none {
      left:500px !important;
      }
        @media only screen and (max-width: 1024px) {
      .highcharts-tooltip .highcharts-color-none:first-child {
        left: -140px !important;
    }}
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
      display:none !important; }
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
        
        
        } 
      
        
        </style>
	</body>
</html>
