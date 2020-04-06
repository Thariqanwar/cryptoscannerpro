<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use \App\CryptoHorizontalResistance;
use \App\Binance;

use \App\Http\Controllers\CommonController;
use App\AlertLog;

// use Ixudra\Curl\Facades\Curl;


class CryptoHRController extends Controller{   

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }

    public static function resistance(){
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

            $low = [];
            $peack = [];
            $open = [];
            $closetime = [];
            foreach ($json_response as $key => $value) {
                $low[] = $value[3];//low values
                $peack[] = $value[2];//takes peak values into array
                $open[] = $value[1];//takes open values into array
                $closetime[] = $value[6];//takes closetime values into array
            }


            /////////////////////////////////Third Logic /////////////////////////
            $open1= $open;
            $minpeak = min($peack);
            // $minpeakKey = array_search($minpeak,$peack);
            
            rsort($open1); // sort the close array to make it dessending order
            if(!isset($open1[2])){
                echo "Tere is no hiting point<br>";
                continue;
            }
            $cmnOpen = $open1[2]; // take the third value from sorted array which will assume as the point which hits 4 times

            $cmnOpenKey = array_search($cmnOpen,$open);
            $time = $closetime[$cmnOpenKey]; // take the time of the point which hits 4 times
            
            $boxArea = (max($peack)-$minpeak)/16; //calculate the highlight box area 
            $boxWidth = (max($closetime)-min($closetime))/100;// calcluate extra covarge area for highlight box in time 

            /////////////////////////////////Third Logic /////////////////////////

            $previous=CryptoHorizontalResistance::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first(); 

            if(!empty($previous)){ // check the values present in database for symbol and intervel
                
                $lastPeak = end($peack);
                echo $endKey = key($peack);echo "<br>";// take the key of last value from new array
                $lastPeakTime = $closetime[$endKey];// take the time of last value from new array
                
                $slicedlow = array_slice($low, $cmnOpenKey);//get low values between hiting point and last peak value
                echo "count ::".count($slicedlow);echo "<br>";
                $avgslicedlow = (count($slicedlow)>3)?array_sum($slicedlow)/count($slicedlow): max($peack);
                
                $minlowToReach = $cmnOpen-($boxArea*3);
                if($avgslicedlow<=$minlowToReach){// testing
                    echo "low reached<br>";
                }else{
                    echo "low NOT reached<br>";
                }

                if($cmnOpen != $previous->maxopen){
                    //compare new point and saved point which hits 4 times, if different save new point
                    $previous->symbol        =  $symbol;
                    $previous->time_interval =  $interval;
                    $previous->maxopen      =  $cmnOpen;
                    $previous->lastpeack = '';
                    $previous->time_1 =  $time-$boxWidth;
                    $previous->peak_1  =  $cmnOpen-$boxArea;
                    $previous->time_2 =  $time-$boxWidth;
                    $previous->peak_2  =  $cmnOpen+$boxArea;
                    // $previous->time_3 =  '';
                    $previous->peak_3  =  $cmnOpen+$boxArea;
                    // $previous->time_4 =  '';
                    $previous->peak_4  =  $cmnOpen-$boxArea;
                    $previous->result =  json_encode($json_response);
                    $previous->screenshort_send = 0;
                    $previous->save();
                    echo "saved new hit point. <br>";
                }
                else if($lastPeak>=$previous->maxopen && $lastPeakTime>$previous->time_3 && $previous->screenshort_send==0 && $avgslicedlow<=$minlowToReach){

                    $previous->lastpeack =  $lastPeak;
                    // $previous->peak_1  =  $previous->maxopen-$boxArea;
                    $previous->time_3 =  $lastPeakTime+$boxWidth;
                    // $previous->peak_3  =  $previous->maxopen + $boxArea;
                    $previous->time_4 =  $lastPeakTime+$boxWidth;
                    // $previous->peak_4  =  $previous->maxopen - $boxArea;
                    $previous->result =  json_encode($json_response);
                    $previous->screenshort_send = 1;
                    $previous->save();

                    $url = 'http://cryptoscannerpro.com/resistanceChart/'.$symbol.'/'.$interval;
                    CommonController::sendScreenshot($url,"BCImage.jpg");//sending screen short
                    echo "screen short send. <br>";
                }
                else if($previous->screenshort_send==1 && $lastPeakTime>$previous->time_3 && end($peack)<$previous->lastpeack ){
                    
                    $previous->screenshort_send = 0;
                    $previous->save();
                    echo "flag reset<br>";
                }
                else{
                    echo "no low values found<br>";
                }
            
            }
            else{
                // save symbol and interval if data not present in table
                $newSave = new CryptoHorizontalResistance;
                $newSave->symbol        =  $symbol;
                $newSave->time_interval =  $interval;
                $newSave->maxopen      =  $cmnOpen;
                $newSave->lastpeack =  '';
                $newSave->time_1 =  $time-$boxWidth;
                $newSave->peak_1  =  $cmnOpen-$boxArea;
                $newSave->time_2 =  $time-$boxWidth;
                $newSave->peak_2  =  $cmnOpen+$boxArea;
                $newSave->time_3 =  '';
                $newSave->peak_3  =  $cmnOpen+$boxArea;
                $newSave->time_4 =  '';
                $newSave->peak_4  =  $cmnOpen-$boxArea;
                $newSave->result =  json_encode($json_response);
                $newSave->screenshort_send = 0;
                $newSave->save();
                echo "new symbol and interval saved<br>";
            }

            echo "--------------------------------------------------<br><br>";
            
        }

        // return $coins;

    }

    public function resistanceChart($symbol,$interval){
    	return view('bigchief/resistanceChart')->with('symbol',$symbol)->with('interval',$interval);
    }

    public function resistanceCandlesticks(Request $request){
    	$symbol=$request->symbol;
    	$interval=$request->interval;
        
        $previous=CryptoHorizontalResistance::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
        $points=[];
        $response=[];
        if(!empty($previous)){
            // $previous->lastpeack;
            $points['time_1']   = $previous->time_1;
            $points['peak_1']    = $previous->peak_1 ;
            $points['time_2']   = $previous->time_2;
            $points['peak_2']    = $previous->peak_2 ;
            $points['time_3']   = $previous->time_3;
            $points['peak_3']    = $previous->peak_3 ;
            $points['time_4']   = $previous->time_4;
            $points['peak_4']    = $previous->peak_4 ;
            $response           = json_decode($previous->result);

            // $previous->save();
            // echo "same->$symbol";
            // $this->hrDivergenceChart($symbol,$interval);
        }
        
        $data=[];
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
        return array($data,$points,$volume);
    }


}

