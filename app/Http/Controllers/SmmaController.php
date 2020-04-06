<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

use \App\Http\Controllers\CommonController;
use \App\Smma;
use \App\Binance;

use Helper;

// use Ixudra\Curl\Facades\Curl;


class SmmaController extends Controller{

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }



    public function curlRequest($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function smmaChart($symbol,$interval){
    	return view('bigchief/smmaChart')->with('symbol',$symbol)->with('interval',$interval);
    }

//////////////

    public static function smma(){
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
                $candelCnt = $value->candle_limit;
                if(!is_array($json_response)){echo "NOT AN ARRAY<br>";continue;}
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

                $fiftymaArray = [];
                $fifty_smma =[];
                $number = 50;
                
                $smmAvarege=0;
                for ($i=0; $i <= $ttArrayCnt; $i++) {
                    if($i<=($ttArrayCnt-$number)){
                        $ffArray = array_slice($json_response,$i,$number,true);

                        ///Calculating sma for first candle
                        $ffsum = 0;
                        foreach($ffArray as $ffkey=>$ffvalue){
                            $ffsum+=$ffvalue[4];//close value
                            $ffclosetime = $ffvalue[6];//close time
                            $currentclose = $ffvalue[4];
                        }

                        if($i==1){
                            $smmAvarege = $ffsum/$number;
                        }else{
                            
                            // SMMA (i) = (SUM1 – SMMA1+CLOSE (i))/ N
                            //// SMMAi = (Sum - SMMAi-1) / N
                            // $smmAvarege = ($ffsum-$smmAvarege)/$number;
                            // (smma[1] * (length - 1) + close) / length
                            $smmAvarege =  ($smmAvarege*($number-1)+$currentclose)/$number;
                        }

                        $ffAvarege = $ffsum/$number;
                        // $fiftyArray[$i]['time'] = $ffclosetime;
                        // $fiftyArray[$i]['ffty_ma'] = $ffAvarege;

                        $fiftymaArray[$i]['time'] = $ffclosetime;
                        $fiftymaArray[$i]['ffty_ma'] = $ffAvarege;

                        $fifty_smma[$i]['time'] = $ffclosetime;
                        $fifty_smma[$i]['ffty_smma'] = $smmAvarege;

                    }
                    
                }

                // for ($i=0; $i <=200 ; $i++) { 
                //     $ffArray1 = array_slice($fiftymaArray,$i,$number,true);

                //     $ffsum1 = 0;
                //     foreach($ffArray1 as $ffkey=>$ffvalue){
                //         $ffsum1+=$ffvalue['ffty_ma'];
                //         $ffclosetime1 = $ffvalue['time'];//close time
                //     }

                //     $ffAvarege1 = $ffsum1/$number;

                //     $fifty_smma[$i]['time'] = $ffclosetime1;
                //     $fifty_smma[$i]['ffty_smma'] = $ffAvarege1;
                // }

                array_shift($fiftymaArray);
                array_shift($fifty_smma);

                $previous=Smma::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
                if(!empty($previous)){
                    $SENDALERT = false;
                    $secondlastffArr = $fiftymaArray[count($fiftymaArray)-3];//third last array
                    $lastffArray = $fiftymaArray[count($fiftymaArray)-2];//second last array

                    $secondlastffsmmaArr = $fifty_smma[count($fifty_smma)-3];//third last array
                    $lastffsmmaArray = $fifty_smma[count($fifty_smma)-2];//second last array
                    // echo "$secondlastffArr<$secondlastffsmmaArr <br>";
                    if($secondlastffArr['ffty_ma']<$secondlastffsmmaArr['ffty_smma']){
                        echo "50ma is below <br>";
                        
                        if($lastffArray['ffty_ma']>=$lastffsmmaArray['ffty_smma']){
                            echo "50ma crossed 50smma <br>";
                            $SENDALERT = true;
                        }else{
                            echo "50ma NOT crossed 50smma <br>";
                        }
                    }

                    else if($secondlastffArr['ffty_ma']>$secondlastffsmmaArr['ffty_smma']){
                        echo "50smma is below <br>";

                        if($lastffArray['ffty_ma']<=$lastffsmmaArray['ffty_smma']){
                            echo "50smma crossed 50ma <br>";
                            $SENDALERT = true;
                        }else{
                            echo "50smma NOT crossed 50ma <br>";
                        }
                    }else{
                        echo "crossed on second last point<br>";
                    }

                    if($previous->time==$lastffArray['time']){
                        $SENDALERT = false;
                    }


                    if($SENDALERT){
                        $url = 'http://cryptoscannerpro.com/smmaChart/'.$symbol.'/'.$interval;
                        CommonController::sendScreenshot($url,"BCImage.jpg");//sending screen short
                        echo "screen short send<br>";
                    }else{
                        echo "screen short NOT send<br>";
                    }

                    // $newfiftyArray = json_decode($previous->fifty_ma, true);
                    // $newfiftysmmaArray = json_decode($previous->fifty_smma, true);
                    // if($lastffArray['time']>end($newfiftyArray)['time']){
                    //     $newfiftyArray[] = $lastffArray;
                    // }
                    // if($lastffsmmaArray['time']>end($newfiftysmmaArray)['time']){
                    //     $newfiftysmmaArray[] = $lastffsmmaArray;
                    // }
                    // echo "COUNT OF 50ma : ".count($newfiftyArray)."<br>";
                    // echo "COUNT OF 50smma : ".count($newfiftysmmaArray)."<br>";
                    // if(count($newfiftyArray)>$ttArrayCnt){
                    //     array_shift($newfiftyArray);
                    // }
                    // if(count($newfiftysmmaArray)>$ttArrayCnt){
                    //     array_shift($newfiftysmmaArray);
                    // }

                    $previous->result              = json_encode($json_response);
                    $previous->screenshort_send    = 0;
                    $previous->fifty_ma            = json_encode($fiftymaArray);
                    $previous->fifty_smma          = json_encode($fifty_smma);
                    $previous->time                = $lastffArray['time'];
                    $previous->save();

                
                }else{
                    $newdata = new Smma;
                    $newdata->symbol            = $symbol;
                    $newdata->time_interval     = $interval;
                    $newdata->result            = json_encode($json_response);
                    $newdata->screenshort_send  = 0;
                    $newdata->fifty_ma          = json_encode($fiftymaArray);
                    $newdata->fifty_smma        = json_encode($fifty_smma);
                    $newdata->save();
                    echo "new symbol and interval saved<br>";
                }
                
                
                echo "------------------------------------<br>";
                        
            }
    }

    public function smmaCandleSticks(Request $request){
    	$symbol=$request->symbol;
    	$interval=$request->interval;

        $previous=Smma::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first(); 

        $response = json_decode($previous->result);
        $fifty_ma = json_decode($previous->fifty_ma, true);
        $fifty_smma = json_decode($previous->fifty_smma, true);
        // print_r($fifty_ma);die;
        $data=[];
        $ffpoints=[];
        $ffsmmapoints=[];
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

        foreach ($fifty_smma as $ffsmmakey => $ffsmmavalue) {
            $ffsmmapoints[$ffsmmakey][0]=floatval($ffsmmavalue['time']);
            $ffsmmapoints[$ffsmmakey][1]=floatval($ffsmmavalue['ffty_smma']);
        }

        return array($data,$ffpoints,$ffsmmapoints,$volume);
    }











}

