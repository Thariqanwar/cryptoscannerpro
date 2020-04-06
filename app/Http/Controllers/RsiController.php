<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use \App\Hrdivergence;
use \App\Binance;
use \App\Rsi;

use \App\Http\Controllers\CommonController;
use \App\Http\Controllers\RsiPrediction;

// use Ixudra\Curl\Facades\Curl;


class RsiController extends Controller{

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }

    public function rsiChart($symbol,$interval){
        return view('bigchief/rsiChart')->with('symbol',$symbol)->with('interval',$interval);
    }

    public static function rsi(){
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

            $api_response = $value->datas;
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
        
                
                $full_rsi=RsiController::calculateRSI($json_response);
                $stsKey = $candelCnt-15;
                
                unset($second_point);
                if(array_key_exists($stsKey, $full_rsi['close']) && array_key_exists($stsKey, $full_rsi['time']) && array_key_exists($stsKey, $full_rsi['rsi']))
                {
                    if( min($full_rsi['close'])==$full_rsi['close'][$stsKey] )
                    {
                    $min_price_1_rsi = $full_rsi['rsi'][$stsKey];
                    if($min_price_1_rsi <=40) 
                    {
                        $second_point=['time'=>$full_rsi['time'][$stsKey],'close'=>$full_rsi['close'][$stsKey],'rsi'=>$full_rsi['rsi'][$stsKey]];
                        $second_point_key=$stsKey; 
                    
                    }
                    } 
                }else{
                    continue ;
                }

                if((isset($second_point)) && (!isset($first_point)))
                {  
                    for ($i=($stsKey-1); $i > 0 ; $i--) 
                    { 
                        if(($full_rsi['close'][$i] > $second_point['close']) && ($full_rsi['rsi'][$i] < $second_point['rsi']) && ($full_rsi['rsi'][$i] <= 30) )
                        {
                            $first_point=['time'=>$full_rsi['time'][$i],'close'=>$full_rsi['close'][$i],'rsi'=>$full_rsi['rsi'][$i]];
                            $first_point_key=$i;
                            $diff=$second_point_key - $first_point_key; /*Number of candle sticks between to points*/
                            $rsi_set=array_slice($full_rsi['rsi'], $first_point_key,$diff);
                            if(count(array_slice($full_rsi['close'], $first_point_key,$diff)) >=7)
                            {
                                $previous=Rsi::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
                                if(!empty($previous)){
                                    $first=strtotime($previous->created_at);
                                    $now= strtotime(date('Y-m-d H:m:s'));
                                    $tinterval  = abs( $first-$now);
                                    $minutes   = round($tinterval / 60);
                                    if($previous->time_2 <= $first_point['time'] )
                                    {
                                    
                                        if(min($rsi_set) >= $first_point['rsi']) /*if rsi less than first point rsi it will not take screenshot*/
                                        {
                                            $previous->symbol =$symbol;
                                            $previous->time_interval =$interval;

                                            $previous->low_price_1 =$first_point['close'];
                                            $previous->low_rsi_1 =$first_point['rsi'];
                                            $previous->time_1 =$first_point['time'];

                                            $previous->low_price_2 = $second_point['close'];
                                            $previous->low_rsi_2  = $second_point['rsi'];
                                            $previous->time_2 = $second_point['time'];
                                            $previous->result = $api_response;
                                            $previous->created_at = date("Y-m-d H:m:s");
                                            if($previous->save())
                                            {
                                                $url = 'http://cryptoscannerpro.com/rsiChart/'.$symbol.'/'.$interval;
                                                CommonController::testsendScreenshot($url,"BCImage.jpg");//sending screen short
                                                RsiPrediction::rsiPrediction($symbol,$interval);
                                                unset($previous);
                                                break 2;  /*break the loop and go to next coin*/
                                            } 
                                        }
                                    }    
                                }else{
                                    
                                    if(min($rsi_set) >= $first_point['rsi']){/*if rsi less than first point rsi it will not take screenshot*/
                                        $set = new Rsi;
                                        $set->symbol=$symbol;
                                        $set->time_interval=$interval;

                                        $set->low_price_1=$first_point['close'];
                                        $set->low_rsi_1=$first_point['rsi'];
                                        $set->time_1=$first_point['time'];

                                        $set->low_price_2=$second_point['close'];
                                        $set->low_rsi_2=$second_point['rsi'];
                                        $set->time_2=$second_point['time'];
                                        $set->result=$api_response;
                                        $set->created_at=date("Y-m-d H:m:s");
                                        if($set->save()){
                                            $url = 'http://cryptoscannerpro.com/rsiChart/'.$symbol.'/'.$interval;
                                            CommonController::testsendScreenshot($url,"BCImage.jpg");//sending screen short
                                            RsiPrediction::rsiPrediction($symbol,$interval);
                                            unset($previous);
                                            unset($set);
                                            break 2;  /*break the loop and go to next coin*/
                                        }else{
                                            echo "Not saved <br>";
                                        }
                                    }  
                                }
                            }else{
                                unset($previous);  
                                unset($first_point);
                                continue;
                            }  
                        }
                    }   
                }
                // }//print_r($status); //foreach close  
            // }
        }
    } //function close

    public static function calculateRSI($datas)
    {             
      $j = 1; $k = 1; $count = ($datas)?count($datas):0;
      $period = 0.07142857; // 1/period  1/14    (RSI period)
      $close = $timestamp = $change = $gain = $loss = $avg_gain = $avg_loss = $rs = $rsi = array();     
      if($datas)
      { 
        foreach($datas as $key => $data)
        {                             
          if(count($datas)< 15)
            break;

          $change[$key] = $datas[$j][4] - $data[4];      
          $gain[$key] = ($change[$key] > 0 )? $change[$key] : 0;
          $loss[$key] = ($change[$key] < 0 )? abs($change[$key]) : 0;     
          if($key == 13)
          {
            $avg_gain[0] = (array_sum($gain)/14);
            $avg_loss[0] = (array_sum($loss)/14);
            if($avg_loss[0] > 0)
            {
              $rs[0] = $avg_gain[0]/$avg_loss[0];
            }
            else
            {
              $rs[0] = 0;
            }
            if($avg_loss[0] > 0)
            {
              $rsi[0] = 100 - (100/(1+$rs[0])); //rsi value
            }
            else
            {
              $rsi[0] = 100; //rsi value
            }
          } 
          if($key > 13)
          {
              $avg_gain[$k] = $gain[$key]*$period+(1-$period)*$avg_gain[$k-1];
              $avg_loss[$k] = $loss[$key]*$period+(1-$period)*$avg_loss[$k-1];                    
              if($avg_loss[$k] > 0)
              {
                  $rs[$k] = $avg_gain[$k]/$avg_loss[$k];
                  $rsi[$k] = 100 - (100/(1+$rs[$k])); //rsi value
              }
              else
              {
                  $rsi[$k][0] = 100; //rsi value
              }
              $k++;
          }
          if($j == ($count-1))
          break;
          $j++;           
        }
      }
      $timestamp=[];
      $close=[];
      $l=0;
      foreach($datas as $key => $data)
      {
        if($key > 13)
        {
          $timestamp[$l]=$data[6];
          $close[$l]=$data[4];
          $l++;
        }
      }      
      return array('time'=>$timestamp,'close'=>$close,'rsi'=>$rsi);
    }

    public function rsicandlesticks(Request $request){
        
        $symbol=$request->symbol;
        $interval=$request->interval;
        // $startTime=1561420800;
        // $endTime=1561507200;
        // $limit=50;
        
        $saved_result=Rsi::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
        $response=$saved_result->result; /*file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);*/
        $response=json_decode($response);
        $data=[];
        foreach ($response as $key => $value) 
        {
            $data[$key][0]=floatval($value[6]); 
            $data[$key][1]=floatval($value[1]);
            $data[$key][2]=floatval($value[2]);
            $data[$key][3]=floatval($value[3]);
            $data[$key][4]=floatval($value[4]);		
        }
        //array_pop($data);
        $points=Rsi::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();

        return array($data,$points);     	    		    	
    }


}

