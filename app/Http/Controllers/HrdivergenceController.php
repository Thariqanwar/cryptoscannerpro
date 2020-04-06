<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use \App\Hrdivergence;
use \App\Binance;

use \App\Http\Controllers\CommonController;

// use Ixudra\Curl\Facades\Curl;


class HrdivergenceController extends Controller{

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }

    public static function hrDivergence(){
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
                
            // $limit=301;
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

            $peak = [];
            $low = [];
            $close = [];
            $closetime = [];
            foreach ($json_response as $key => $value) {
                $peak[] = $value[2];
                $low[] = $value[3];//takes low values into array
                $close[] = $value[4];//takes close values into array
                $closetime[] = $value[6];//takes closetime values into array
            }


            /////////////////////////////////Third Logic /////////////////////////
            $close1= $close;
            $minlow = min($low);
            // $minlowKey = array_search($minlow,$low);
            
            sort($close1); // sort the close array to make it assending order
            if(!isset($close1[2])){
                echo "Tere is no hiting point<br>";
                continue;
            }
            $cmnClose = $close1[2]; // take the third value from sorted array which will assume as the point which hits 4 times

            $cmnCloseKey = array_search($cmnClose,$close);
            $time = $closetime[$cmnCloseKey]; // take the time of the point which hits 4 times
            
            $boxArea = (max($low)-$minlow)/16; //calculate the highlight box area 
            $boxWidth = (max($closetime)-min($closetime))/100;// calcluate extra covarge area for highlight box in time 

            /////////////////////////////////Third Logic /////////////////////////

            $previous=Hrdivergence::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first(); 

            if(!empty($previous)){ // check the values present in database for symbol and intervel
                // $previousMinClose = $previous->minclose;// previous point which hits 4 times, saved in database
                // $newLowArray = array_filter($low, function($e) use($previousMinClose) {return ($e<=$previousMinClose);});// select all low points which are less than point which hits 4 times
                // if(empty($newLowArray)){
                //     continue;// if no values skip to next
                // }
                
                $lastLow = end($low);// take the last value from new array
                $endKey = key($low);// take the key of last value from new array
                $lastLowTime = $closetime[$endKey];// take the time of last value from new array

                $slicedpeak = array_slice(array_chunk($peak,$endKey)[0], $cmnCloseKey);//get peak values between hiting point and last low value
                $avgslicedpeak = (count($slicedpeak)>2)?array_sum($slicedpeak)/count($slicedpeak):0;
                
                $maxPeakToReach = $cmnClose+($boxArea*3);
                if($avgslicedpeak>=$maxPeakToReach){// testing
                    echo "peak reached<br>";
                }else{
                    echo "peak NOT reached<br>";
                }

                if($cmnClose != $previous->minclose){
                    //compare new point and saved point which hits 4 times, if different save new point
                    $previous->symbol        =  $symbol;
                    $previous->time_interval =  $interval;
                    $previous->minclose      =  $cmnClose;
                    $previous->lastlow = '';
                    $previous->time_1 =  $time-$boxWidth;
                    $previous->low_1  =  $cmnClose-$boxArea;
                    $previous->time_2 =  $time-$boxWidth;
                    $previous->low_2  =  $cmnClose+$boxArea;
                    // $previous->time_3 =  '';
                    $previous->low_3  =  $cmnClose+$boxArea;
                    // $previous->time_4 =  '';
                    $previous->low_4  =  $cmnClose-$boxArea;
                    $previous->result =  json_encode($json_response);
                    $previous->screenshort_send = 0;
                    $previous->save();
                    echo "saved new hit point. <br>";
                }
                else if($lastLow<=$previous->minclose && $lastLowTime>$previous->time_3 && $previous->screenshort_send==0 && $avgslicedpeak>=$maxPeakToReach){
                    //comapre last low with saved point of hit and 
                    //last low_time with saved last_low_point_time(time cordinate of 3rd point of highlight box) and
                    //check flg that screen short send or not (0->not send, 1->send) and
                    //check peak point reached to certain value for next screenshort(to prevent screenshort send imidiate first)
                    //if conditions match save new vales and send screeen short
                    // this checking is for prevent sending scrennshort for each low point which will lower than hitpoint
                    // seting flag will prevent this 

                    $previous->lastlow =  $lastLow;
                    // $previous->low_1  =  $previous->minclose-$boxArea;
                    $previous->time_3 =  $lastLowTime+$boxWidth;
                    // $previous->low_3  =  $previous->minclose + $boxArea;
                    $previous->time_4 =  $lastLowTime+$boxWidth;
                    // $previous->low_4  =  $previous->minclose - $boxArea;
                    $previous->result =  json_encode($json_response);
                    $previous->screenshort_send = 1;
                    $previous->save();

                    $url = 'http://cryptoscannerpro.com/hrDivergenceChart/'.$symbol.'/'.$interval;
                    CommonController::sendScreenshot($url,"BCImage.jpg");//sending screen short
                    echo "screen short send. <br>";
                }
                else if($previous->screenshort_send==1 && $lastLowTime>$previous->time_3 && end($low)>$previous->lastlow ){
                    //check flg that screen short send or not (0->not send, 1->send) and
                    //last low_time with saved last_low_point_time(time cordinate of 3rd point of highlight box) and
                    //the last value of low points with saved lowpoint 
                    //this checking is for when the low point reched above last low point and reset flag
                    
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
                $newSave = new Hrdivergence;
                $newSave->symbol        =  $symbol;
                $newSave->time_interval =  $interval;
                $newSave->minclose      =  $cmnClose;
                $newSave->lastlow =  '';
                $newSave->time_1 =  $time-$boxWidth;
                $newSave->low_1  =  $cmnClose-$boxArea;
                $newSave->time_2 =  $time-$boxWidth;
                $newSave->low_2  =  $cmnClose+$boxArea;
                $newSave->time_3 =  '';
                $newSave->low_3  =  $cmnClose+$boxArea;
                $newSave->time_4 =  '';
                $newSave->low_4  =  $cmnClose-$boxArea;
                $newSave->result =  json_encode($json_response);
                $newSave->screenshort_send = 0;
                $newSave->save();
                echo "new symbol and interval saved<br>";
            }

            echo "--------------------------------------------------<br><br>";
            
        }

        // return $coins;

    }

    public function hrDivergenceChart($symbol,$interval){
    	return view('bigchief/hrDivergenceChart')->with('symbol',$symbol)->with('interval',$interval);
    }

    public function CandleSticks(Request $request){
    	$symbol=$request->symbol;
    	$interval=$request->interval;
        
        $previous=Hrdivergence::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
        $points=[];
        $response=[];
        if(!empty($previous)){
            // $previous->lastlow;
            $points['time_1']   = $previous->time_1;
            $points['low_1']    = $previous->low_1 ;
            $points['time_2']   = $previous->time_2;
            $points['low_2']    = $previous->low_2 ;
            $points['time_3']   = $previous->time_3;
            $points['low_3']    = $previous->low_3 ;
            $points['time_4']   = $previous->time_4;
            $points['low_4']    = $previous->low_4 ;
            $response           = json_decode($previous->result);

            // $previous->save();
            // echo "same->$symbol";
            // $this->hrDivergenceChart($symbol,$interval);
        }
        
        $data=[];
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
        return array($data,$points,$volume);
    }


}

