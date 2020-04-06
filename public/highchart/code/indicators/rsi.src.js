/**
 * @license  Highcharts JS v7.1.2 (2019-06-03)
 *
 * Indicator series type for Highstock
 *
 * (c) 2010-2019 Paweł Fus
 *
 * License: www.highcharts.com/license
 */
'use strict';
(function (factory) {
    if (typeof module === 'object' && module.exports) {
        factory['default'] = factory;
        module.exports = factory;
    } else if (typeof define === 'function' && define.amd) {
        define('highcharts/indicators/rsi', ['highcharts', 'highcharts/modules/stock'], function (Highcharts) {
            factory(Highcharts);
            factory.Highcharts = Highcharts;
            return factory;
        });
    } else {
        factory(typeof Highcharts !== 'undefined' ? Highcharts : undefined);
    }
}(function (Highcharts) {

    var _modules = Highcharts ? Highcharts._modules : {};
    function _registerModule(obj, path, args, fn) {
        if (!obj.hasOwnProperty(path)) {
            obj[path] = fn.apply(null, args);
        }
    }
    _registerModule(_modules, 'indicators/rsi.src.js', [_modules['parts/Globals.js']], function (H) {
        /* *
         *
         *  License: www.highcharts.com/license
         *
         * */



        var isArray = H.isArray;

        // Utils:
        function toFixed(a, n) {
            return parseFloat(a.toFixed(n));
        }

        /**
         * The RSI series type.
         *
         * @private
         * @class
         * @name Highcharts.seriesTypes.rsi
         *
         * @augments Highcharts.Series
         */
        H.seriesType(
            'rsi',
            'sma',
            /**
             * Relative strength index (RSI) technical indicator. This series
             * requires the `linkedTo` option to be set and should be loaded after
             * the `stock/indicators/indicators.js` file.
             *
             * @sample stock/indicators/rsi
             *         RSI indicator
             *
             * @extends      plotOptions.sma
             * @since        6.0.0
             * @product      highstock
             * @optionparent plotOptions.rsi
             */
            {
                /**
                 * @excluding index
                 */
                params: {
                    period: 0.07142857,
                    /**
                     * Number of maximum decimals that are used in RSI calculations.
                     */
                    decimals: 4
                }
            },
            /**
             * @lends Highcharts.Series#
             */
            {
                getValues: function (series, params) {
                    var period = params.period,
                        xVal = series.xData, /*timestamps*/
                        yVal = series.yData, /*values*/
                        yValLen = yVal ? yVal.length : 0,
                        decimals = params.decimals,
                        // RSI starts calculations from the second point
                        // Cause we need to calculate change between two points
                        values=[],
                        RSI=[],
                        xData = [],
                        yData = [],
                        index = 3, 
                        j =1,
                        k = 1,
                        count,
                        i,
                        change = [], gain =[], loss =[], avg_gain =[], avg_loss =[], rs =[], rsi =[]; 

                        count = (yVal) ? (yVal.length):0; /*50*/
                        if(yVal)
                        {
                           
                           for (i = 0; i < count; i++) 
                            { 
                                if(count < 15) { break;}
                                change[i] = yVal[j][index] - yVal[i][index]; 
                                gain[i] = (change[i] > 0 )? change[i] : 0;
                                loss[i] = (change[i] < 0 )? Math.abs(change[i]) : 0;
                                if(i == 13){
                                    avg_gain[0] = (gain.reduce((a,b) => a + b, 0)/14); 
                                    avg_loss[0] = (loss.reduce((a,b) => a + b, 0)/14);
                                    if(avg_loss[0] > 0){
                                        rs[0] = avg_gain[0]/avg_loss[0];
                                    }else{
                                        rs[0] = 0;
                                    }
                                    if(avg_loss[0] > 0){
                                        rsi[0] = 100 - (100/(1+rs[0]));
                                    }else{
                                        rsi[0] = 100;
                                    }
                                }
                                if(i > 13){
                                    avg_gain[k] = gain[i]*period+(1-period)*avg_gain[k-1];
                                    avg_loss[k] = loss[i]*period+(1-period)*avg_loss[k-1];                    
                                    if(avg_loss[k] > 0){
                                        rs[k] = avg_gain[k]/avg_loss[k];
                                        rsi[k] = 100 - (100/(1+rs[k]));
                                    }else{
                                        rsi[k] = 100;
                                    }
                                    k++;
                                }

                               
                                if(j == (count-1))
                                    break;
                                j++;      
                                
                                //RSI.push([xVal[i], rsi[i]]);
                                xData.push(xVal[i]);
                                yData=rsi;

                            }
                                
                            xVal=xVal.slice(14);
                            //xData=xData.slice(12);
                            for (i = 0; i < xVal.length; i++) 
                                { 
                                     RSI.push([xVal[i], rsi[i]]);
                                    
                                }
                            /*console.log(RSI);  
                            console.log(xData);  
                            console.log(yData); */ 
                            return {   
                                values:RSI,
                                xData:xData,
                                yData:yData,
                            }
                            

                        } /*if close*/
                       
                }    
            
    
});

        /**
         * A `RSI` series. If the [type](#series.rsi.type) option is not
         * specified, it is inherited from [chart.type](#chart.type).
         *
         * @extends   series,plotOptions.rsi
         * @since     6.0.0
         * @product   highstock
         * @excluding dataParser, dataURL
         * @apioption series.rsi
         */

    });
    _registerModule(_modules, 'masters/indicators/rsi.src.js', [], function () {


    });
}));
