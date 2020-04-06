<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

use \App\Http\Controllers\CommonController;

use \App\BollingerBand;
use \App\Binance;

// use Helper;

// use Ixudra\Curl\Facades\Curl;


class BollingerController extends Controller{

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }


    public function bollingerChart($symbol,$interval){
    	return view('bigchief/bollingerChart')->with('symbol',$symbol)->with('interval',$interval);
    }

    public static function bollingerband(){
        $interval_list=['1h'];
        $limit_list = [300];
        $quoteVolume = 150;
        $Binancedata=Binance::whereIn('candle_interval',$interval_list)
                                ->whereIn('candle_limit',$limit_list)
                                ->where('quoteVolume','>=',$quoteVolume)
                                ->get();
            
            $upperBandArray=[];
            $lowerBandArray=[];
            $closeTimeArray=[];
            $smaArray = [];

            foreach ($Binancedata as $key => $value) {
                $symbol = $value->symbol;
                $interval = $value->candle_interval;
                    
                echo "<br>$key---$interval---$symbol-------<br>";
    
                $json_response = json_decode($value->datas);
                // array_pop($json_response);
                if(!is_array($json_response)){echo "NOT AN ARRAY<br>";continue;}
                $candelCnt = $value->candle_limit;
                if(empty($json_response) || count($json_response)<$candelCnt){// check count of candles
                    echo "NO DATA FOUND OR COUNT OF CANDLE IS LESS.<br>";
                    continue;
                }

                $clsvalue = [];
                foreach ($json_response as $jsonkey => $jsonvalue) { $clsvalue[] = $jsonvalue[4]; }
                $valueCount = array_count_values($clsvalue);// get count of each value into new array
                if(max($valueCount)>100){// Skip if a value is repaeting more than 100 times 
                    echo max($valueCount)." REPETING VALUES<br>";continue;
                }

                $ttArrayCnt = count($json_response);
                echo "$ttArrayCnt <br>";

                $smaArray = [];
                $number = 20;

                $upperBandArray=[];
                $lowerBandArray=[];
                $bandwidthArray=[];
                $closeTimeArray=[];
                
                for ($i=0; $i <= $ttArrayCnt; $i++) {
                    if($i<=($ttArrayCnt-$number)){
                        $ffArray = array_slice($json_response,$i,$number,true);

                        ///Calculating sma
                        $ffsum = 0;
                        foreach($ffArray as $ffkey=>$ffvalue){
                            $ffsum+=$ffvalue[4];//close value
                            $ffclosetime = $ffvalue[6];//close time
                        }
                        $smAvarege = $ffsum/$number;
                        $smaArray[$i]['time'] = $ffclosetime;
                        $smaArray[$i]['sma'] = $smAvarege;
                        $closeTimeArray[] = $ffclosetime;

                        ///calculating Standard Deviation
                        $sqSum=0;
                        foreach($ffArray as $key1=>$value1){
                            $closeVal=$value1[4];//close value
                            $closetime = $value1[6];//close time
                            $sqSum+=pow(($smAvarege-$closeVal),2);
                        }
                        $stndDev = sqrt($sqSum/($number-1));//calculate standers deviation

                        $upperBand = $smAvarege+($stndDev*2);//Upperband points
                        $lowerBand = $smAvarege-($stndDev*2);//lowerband points

                        $smaArray[$i]['upper'] = $upperBand;
                        $smaArray[$i]['lower'] = $lowerBand;

                        $upperBandArray[] = $upperBand;
                        $lowerBandArray[] = $lowerBand;
                        $bandwidthArray[] = $upperBand-$lowerBand;

                    }
                    
                }


                $previous=BollingerBand::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();

                if(!empty($previous)){
                    $SENDALERT = false;
                    
                    $minbbWidth = (max($upperBandArray)-min($lowerBandArray))*(5/100);
                    $maxbbWidth = (max($upperBandArray)-min($lowerBandArray))*(20/100);
                    $lastsmaArray1 = $smaArray[count($smaArray)-1];

                    $chackCndlNo = 15;

                    if(count($smaArray)<$chackCndlNo){continue;}
                    $checkCnt = 0;
                    for ($i=1; $i <= $chackCndlNo; $i++) {
                        $lastsmaArray = $smaArray[count($smaArray)-$i];
                        // ( (Upper Band - Lower Band) / Middle Band) * 100    band width equation
                        // $lastbbWidth = (($lastsmaArray['upper']-$lastsmaArray['lower'])/$lastsmaArray['sma'])*100 ;
                        $lastbbWidth = (($lastsmaArray['upper']-$lastsmaArray['lower'])) ;
                        // echo "$lastbbWidth<=$minbbWidth <br>";
                        if($lastbbWidth<=$minbbWidth){ $checkCnt+= 1; }
                    }
                    if($checkCnt==$chackCndlNo){ 
                        $nthArray = $smaArray[count($smaArray)-($chackCndlNo+1)];
                        $nthbandwidth = $nthArray['upper']-$nthArray['lower'];
                        if($nthbandwidth>=$maxbbWidth){
                            $previousTimeKey = array_search($previous->time,$closeTimeArray);
                            $slicedBandwidthArray = array_slice($bandwidthArray,$previousTimeKey);
                            if(max($slicedBandwidthArray)>$minbbWidth){$SENDALERT = true;}//check 
                            else{$SENDALERT = false;}
                        }else{
                            $SENDALERT = false;
                        }
                        // echo max($slicedBandwidthArray).">".$minbbWidth."<br>";
                    }else{
                        $SENDALERT = false;
                    }
                    // if($lastsmaArray['time']<$previous->time){  $SENDALERT = false; }

                    echo "'$SENDALERT'<br>";
                    if($SENDALERT){
                        $url = 'http://cryptoscannerpro.com/bollingerChart/'.$symbol.'/'.$interval;
                        CommonController::testsendScreenshot($url,"BCImage.jpg");//sending screen short
                        echo "screen short send<br>";
                    }else{
                        echo "screen short NOT send<br>";
                    }

                    $previous->result              = json_encode($json_response);
                    $previous->screenshort_send    = 0;
                    $previous->sma                 = json_encode($smaArray);
                    $previous->time                = $lastsmaArray1['time'];
                    $previous->save();
                }else{
                    $newdata = new BollingerBand;
                    $newdata->symbol            = $symbol;
                    $newdata->time_interval     = $interval;
                    $newdata->result            = json_encode($json_response);
                    $newdata->screenshort_send  = 0;
                    $newdata->sma               = json_encode($smaArray);
                    $newdata->save();
                    echo "new symbol and interval saved<br>";
                }
                
                echo "------------------------------------<br>";
            }


    }

    public function bollingerCandleSticks(Request $request){
    	$symbol=$request->symbol;
    	$interval=$request->interval;

        $previous=BollingerBand::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first(); 

        $response = json_decode($previous->result);
        $sma = json_decode($previous->sma, true);

        // print_r($response);

        $data=[];
        $smapoints=[];
        $upperpoints=[];
        $lowerpoints=[];
        $volume = [];
    	foreach ($response as $key => $value){
    		$data[$key][0]=floatval($value[6]);
    		$data[$key][1]=floatval($value[1]);
    		$data[$key][2]=floatval($value[2]);
    		$data[$key][3]=floatval($value[3]);
            $data[$key][4]=floatval($value[4]);

            $volume[$key][0]=floatval($value[6]);
            $volume[$key][1]=floatval($value[7]);
        }

        foreach ($sma as $key => $value) {
            $smapoints[$key][0]=floatval($value['time']);
            $smapoints[$key][1]=floatval($value['sma']);

            $upperpoints[$key][0]=floatval($value['time']);
            $upperpoints[$key][1]=floatval($value['upper']);
            $upperpoints[$key][2]=floatval($value['lower']);

            $lowerpoints[$key][0]=floatval($value['time']);
            $lowerpoints[$key][1]=floatval($value['lower']);

        }

        return array($data,$smapoints,$upperpoints,$lowerpoints,$volume);
    }











}

