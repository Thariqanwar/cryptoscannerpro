<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Ixudra\Curl\Facades\Curl;

use App\Model\Feedback;
use App\CryptoHorizontalSupport;
use App\MovingAverage;
use Carbon\Carbon;
use App\TimeFrame;
use App\User;
use App\Binance;
use App\AlertLog;


// use Ixudra\Curl\Facades\Curl;


class CryptoHSController extends Controller
{
 
    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }
    public static function HorizontalSupport(){


        $interval_list=['5m','15m','1h','4h'];
        $limit_list = [300];
        $quoteVolume = 150;

        $Binancedata=Binance::whereIn('candle_interval',$interval_list)
                                ->whereIn('candle_limit',$limit_list)
                                /*->where('quoteVolume','>=',$quoteVolume)*/
                                ->get();
       
        foreach ($Binancedata as $key => $value) {
           /* $botToken="979168781:AAFa7WMSZynf26kYsJdPATzPqLc1N-DgTCs";
            $message=$value;
            $chat_id='-1001490750483';
            $url="https://api.telegram.org/bot$botToken/sendMessage?chat_id=".$chat_id."&text=".$message;
            //dd($url); 
            $ch = curl_init($url);                     
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            //dd($data);
            curl_close($ch);*/
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

            $previous=CryptoHorizontalSupport::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first(); 

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





                    $url = 'http://cryptoscannerpro.com/hrDivergence/'.$symbol.'/'.$interval;
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
                $newSave = new CryptoHorizontalSupport;
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
    public function hrDivergence($symbol,$interval){
    	return view('hr_divergance')->with('symbol',$symbol)->with('interval',$interval);
    }

    public function CandleSticks(Request $request){
    	$symbol=$request->symbol;
    	$interval=$request->interval;
        
        $previous=CryptoHorizontalSupport::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
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
            // $this->hrDivergence($symbol,$interval);
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

    public static function hrscreenshot($symbol,$interval,$url)
    {
        
       
         $image='/home/cspxxvk1/public_html/public/telegram/HrD.jpg';
        //print_r($image);
         //$url = 'http://cryptoscannerpro.com/symbol/'.$symbol.'/'.$interval;
        //$image = 'Images.jpeg';
        $accountKey = 'ee0f48';
        $key="eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tpZCI6IjE1NjMyNTM2NDg0MDctNSIsImVtYWlsIjoiY3J5cHRvc2Nhbm5lcnByb0BnbWFpbC5jb20iLCJzZXJ2aWNlIjoic2NyZWVuLnJpcCIsInBsYW4iOjEwMCwiaWF0IjoxNTYzMjUzNjQ4LCJleHAiOjE1OTQ3ODk2NDh9.MmPRgbb7mdC41AxPTmph89ALyLlqV8xEpeDnwvZ1o6YVg2MUlghoHZPD6usmpoUlNrpjQicNnzy5yEz1qiIQaxXlNYjaX_1TaWSJ3paNXLzroenuPtEZomqGbvPP6OUXstZ1Eq2JOUXuv-9qD0ieXIT9kvFkr0XazoNbY2sHbFON1vMU7wic0cybFVbFr-uFqh2ZncrKTtLwtyI9ZsOOJA3F9ElI-6kj9pJxafnL4mksVRsY7gBB7eYVs1G36_ExNg2mjBafaecXEjaoz3FK6kZFl65hZ7bForej4jDQfFJrhfUSceeMVzdOGqyybInO9Q9eLu1Xutmuc2k0bJyI1obASe1Ju2iRK2uYgGWluvpi9ZXDl5uSgFZhxh_FcX3fKGUFjBQeTx_rw7WLlZW2byXZ6WSTBwBLPJ38kc1HrNqekmxsx3feINHiEvpagyDOCvGuKNVLOh53IaZLmQQscgkgaEYhPuLrzlvDpdzrXQJcfgwS5x6gTKTereuJqZB81e68m4RrVQkYmx06LWw7nmhg8H_cg0ACC1kLc8HlSs0FAXGUxM0Uo8iyd1IETbqOdOkifrtpls0dXS_HuzSbX-dJcNRvVUFx5ZMp9CUu6AkBuh5EcR2rmmHqvJ9p6mkEc-5dhQURzWU-UPTffJsNvA4Cjma2PYvPBsUsbmhwJQg";
        
        //$ch = curl_init('https://screen.rip/capture?token='.$key.'&url='.$url.'&wait=5000');
        $ch = curl_init('http://api.screenshotmachine.com/?key=ee0f48&dimension=1024x786&delay=8000&cacheLimit=0&url='.$url);
        //print_r($ch);
        //$ch = curl_init('http://api.screenshotmachine.com/?key='.$accountKey.'&size=M&format=png&url='.$url);
        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $time=TimeFrame::where('short',$interval)->first();
        if($time->subscription)
        {
            foreach($time->subscription as $subscription)
            {
                if($subscription->user)
                {
                    $user_list[]=$subscription->user->id; 
                }
            }  
        }            
        $user_list=User::whereIn('id',$user_list)->where('status',1)->get();
        if($user_list)
        {
            $alert_log=new AlertLog;
            $alert_log->coin=$symbol;
            $alert_log->time_interval=$interval;
            $alert_log->created_at=date("Y-m-d h:i:s");
            $current_price=LowPrice::where('symbol',$symbol)->where('time_interval',$interval)->first();
            $alert_log->price='';
            $alert_log->price_2='';
            $alert_log->category=5; /*crypto horizontal suuport*/
            $alert_log->save();
            $filename=$alert_log->id;
            $target=public_path().'/telegram/'.$filename.'.jpg';

            copy($image,$target);
          foreach ($user_list as $key => $user) 
          {
            if($user->telegram_chat)
            { 
                $chat_id    = /*'-1001496915788'*/$user->telegram_chat->chat_id; 
                $bot_url    = "https://api.telegram.org/bot743896776:AAFC2nou6yKHom3-trRtdYar_7mEU3iFTE8/";
                $urls        = $bot_url."sendPhoto?chat_id=".$chat_id;
                $c[]=$urls;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); 
                $post_fields = array(
                    'photo'     => new \CURLFile(realpath($image))
                    
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type:multipart/form-data"
                ));
                curl_setopt($ch, CURLOPT_URL, $urls); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
               
                $output[] = curl_exec($ch); 
            }    
          }  
      }         

        
        print_r($output);
    }
    

}    
