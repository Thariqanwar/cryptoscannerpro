<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use \App\Movingaverage;
use \App\Binance;

use \App\Http\Controllers\CommonController;

// use Ixudra\Curl\Facades\Curl;


class MovingaverageController extends Controller{

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }

    public function movingaverageChart($symbol,$interval){
    	return view('bigchief/movingavarageChart')->with('symbol',$symbol)->with('interval',$interval);
    }

    public static function movingaverage(){
        $interval_list=['1h'];
        $limit_list = [300];
        $quoteVolume = 150;
        $Binancedata=Binance::whereIn('candle_interval',$interval_list)
                                ->whereIn('candle_limit',$limit_list)
                                ->where('quoteVolume','>=',$quoteVolume)
                                ->get();

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
            $fiftyArray = [];
            $twohundredArray = [];
            for ($i=0; $i <= $ttArrayCnt; $i++) {
                
                if($i<=($ttArrayCnt-50)){
                    $ffArray = array_slice($json_response,$i,50,true);
                    $ffsum = 0;
                    foreach($ffArray as $ffkey=>$ffvalue){
                        $ffsum+=$ffvalue[4];
                        $ffclosetime = $ffvalue[6];
                    }
                    $ffAvarege = $ffsum/50;
                    $fiftyArray[$i]['time'] = $ffclosetime;
                    $fiftyArray[$i]['ffty_ma'] = $ffAvarege;
                }

                if($i<=($ttArrayCnt-200)){
                    $twohndArray = array_slice($json_response,$i,200,true);
                    $twohndsum = 0;
                    foreach($twohndArray as $twohndkey=>$twohndvalue){
                        $twohndsum+=$twohndvalue[4];
                        $twohndclosetime = $twohndvalue[6];
                    }
                    $twohndAvarege = $twohndsum/200;
                    $twohundredArray[$i]['time'] = $twohndclosetime;
                    $twohundredArray[$i]['twohnd_ma'] = $twohndAvarege;
                }
                
            }

            if(count($fiftyArray)<4){ continue; }//skip the loop if not enough element in array
            // print_r(json_encode($fiftyArray));
            $previous=Movingaverage::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();

            if(!empty($previous)){
                $SENDALERT = false;
                $secondlastffArr = $fiftyArray[count($fiftyArray)-3];// third last array
                $lastffArray = $fiftyArray[count($fiftyArray)-2];//second lasy array

                $secondlasttwohndArr = $twohundredArray[count($twohundredArray)-3];// third last array
                $lasttwohndArray = $twohundredArray[count($twohundredArray)-2];//second lasy array
                // echo "$secondlastffArr<$secondlasttwohndArr <br>";
                if($secondlastffArr['ffty_ma']<$secondlasttwohndArr['twohnd_ma']){
                    echo "50ma is below <br>";
                    
                    if($lastffArray['ffty_ma']>=$lasttwohndArray['twohnd_ma']){
                        echo "50ma crossed 200ma <br>";
                        $SENDALERT = true;
                    }else{
                        echo "50ma NOT crossed 200ma <br>";
                    }
                }

                else if($secondlastffArr['ffty_ma']>$secondlasttwohndArr['twohnd_ma']){
                    echo "200ma is below <br>";

                    if($lastffArray['ffty_ma']<=$lasttwohndArray['twohnd_ma']){
                        echo "200ma crossed 50ma <br>";
                        $SENDALERT = true;
                    }else{
                        echo "200ma NOT crossed 50ma <br>";
                    }
                }else{
                    echo "crossed on second last point<br>";
                }

                if($previous->time==$lastffArray['time']){
                    $SENDALERT = false;
                }

                // $SENDALERT = true;
                if($SENDALERT){
                    $url = 'http://cryptoscannerpro.com/movingaverageChart/'.$symbol.'/'.$interval;
                    CommonController::sendScreenshot($url,"BCImage.jpg");//sending screen short
                    echo "screen short send<br>";
                }else{
                    echo "screen short NOT send<br>";
                }

                // $newfiftyArray = json_decode($previous->fifty_ma, true);
                // $newtwohundredArray = json_decode($previous->twohundred_ma, true);

                // if($lastffArray['time']>end($newfiftyArray)['time']){
                //     $newfiftyArray[] = $lastffArray;
                // }
                // if($lasttwohndArray['time']>end($newtwohundredArray)['time']){
                //     $newtwohundredArray[] = $lasttwohndArray;
                // }
                // echo "COUNT OF 50ma : ".count($newfiftyArray)."<br>";
                // echo "COUNT OF 200ma : ".count($newtwohundredArray)."<br>";

                // if(count($newfiftyArray)>$ttArrayCnt){
                //     array_shift($newfiftyArray);
                // }
                // if(count($newtwohundredArray)>$ttArrayCnt){
                //     array_shift($newtwohundredArray);
                // }

                $previous->result              = json_encode($json_response);
                $previous->screenshort_send    = 0;
                $previous->fifty_ma            = json_encode($fiftyArray);
                $previous->twohundred_ma       = json_encode($twohundredArray);
                $previous->time                = $lastffArray['time'];
                $previous->save();
            }else{
                $newma = new Movingaverage;
                $newma->symbol              = $symbol;
                $newma->time_interval       = $interval;
                $newma->result              = json_encode($json_response);
                $newma->screenshort_send    = 0;
                $newma->fifty_ma         = json_encode($fiftyArray);
                $newma->twohundred_ma    = json_encode($twohundredArray);
                $newma->save();
                echo "new symbol and interval saved<br>";
            }
            
            echo "------------------------------------<br>";
        }

    }

    public function movingAvaregeCandleSticks(Request $request){
    	$symbol=$request->symbol;
    	$interval=$request->interval;

        $previous=Movingaverage::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first(); 

        $response = json_decode($previous->result);
        $fifty_ma = json_decode($previous->fifty_ma, true);
        $twohundred_ma = json_decode($previous->twohundred_ma, true);
        // print_r($fifty_ma);die;
        $data=[];
        $ffpoints=[];
        $twohndpoints=[];
        $volume=[];
    	foreach ($response as $key => $value){
    		$data[$key][0]=floatval($value[6]);
    		$data[$key][1]=floatval($value[1]);
    		$data[$key][2]=floatval($value[2]);
    		$data[$key][3]=floatval($value[3]);
            $data[$key][4]=floatval($value[4]);

            $volume[$key][0]=floatval($value[6]);
            $volume[$key][1]=floatval($value[7]);
        }

        foreach ($fifty_ma as $ffkey => $ffvalue) {
            $ffpoints[$ffkey][0]=floatval($ffvalue['time']);
            $ffpoints[$ffkey][1]=floatval($ffvalue['ffty_ma']);
        }

        foreach ($twohundred_ma as $twohndkey => $twohndvalue) {
            $twohndpoints[$twohndkey][0]=floatval($twohndvalue['time']);
            $twohndpoints[$twohndkey][1]=floatval($twohndvalue['twohnd_ma']);
        }

        return array($data,$ffpoints,$twohndpoints,$volume);
    }


}

