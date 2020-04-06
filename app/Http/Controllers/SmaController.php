<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Ixudra\Curl\Facades\Curl;

use App\Model\Feedback;
use App\Hrdivergence;
use App\MovingAverage;
use Carbon\Carbon;
use App\TimeFrame;
use App\User;
use App\AlertLog;


// use Ixudra\Curl\Facades\Curl;


class SmaController extends Controller
{

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }

    public static function movingAverage()
    {
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/ticker/24hr');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
        $coins = curl_exec($curl_handle);
        curl_close($curl_handle);
        $coin_list = json_decode($coins);
        $coins=[];
        
        foreach ($coin_list as $key => $coin){
            if('BTC'==substr($coin->symbol, -3) || $coin->symbol=='BTCUSDT' || $coin->symbol=='ETHUSDT'){
                // echo $coin->symbol." => ".$coin->quoteVolume."<br>";
                if($coin->quoteVolume>100){
                    $coins[]=$coin->symbol;
                }
            }
        }
        
        $ma1=50;
        $ma2=200;
        
        $interval_list=['15m'];
        foreach ($interval_list as $key => $interval)
        {
           foreach ($coins as $key => $symbol)
           {
           
               $limit=300;
               echo "<br>$key---$interval---$symbol-------<br>";

               ///////// Take Current values for comparing and plot graph////////////////
               $curl_handle=curl_init();
               curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);
               curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
               curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
               curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
               $api_response = curl_exec($curl_handle);
               curl_close($curl_handle);
               $json_response = json_decode($api_response);
               // array_pop($json_response);

                if(empty($json_response))
                {
                   echo "NO DATA FOUND<br>";
                   continue;
                }

                $ttArrayCnt = count($json_response);

                $fiftyArray = [];
                $twohundredArray = [];
                for ($i=0; $i <= $ttArrayCnt; $i++) 
                {
                   if($i<=($ttArrayCnt-$ma1))
                   {
                       $ffArray = array_slice($json_response,$i,$ma1,true);
                       $ffsum = 0;
                       foreach($ffArray as $ffkey=>$ffvalue)
                       {
                           $ffsum+=$ffvalue[4];
                           $ffclosetime = $ffvalue[6];
                       }
                       $ffAvarege = $ffsum/$ma1;
                       $fiftyArray[$i]['time'] = $ffclosetime;
                       $fiftyArray[$i]['first_ma'] = $ffAvarege;
                   }

                   if($i<=($ttArrayCnt-$ma2))
                   {
                       $twohndArray = array_slice($json_response,$i,$ma2,true);
                       $twohndsum = 0;
                       foreach($twohndArray as $twohndkey=>$twohndvalue)
                       {
                           $twohndsum+=$twohndvalue[4];
                           $twohndclosetime = $twohndvalue[6];
                       }
                       $twohndAvarege = $twohndsum/$ma2;
                       $twohundredArray[$i]['time'] = $twohndclosetime;
                       $twohundredArray[$i]['second_ma'] = $twohndAvarege;
                   }
                }

                // print_r(json_encode($fiftyArray));
                $previous=MovingAverage::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();

                if(!empty($previous))
                {
                   $SENDALERT = false;
                   $secondlastffArr = $fiftyArray[count($fiftyArray)-2];
                   $lastffArray = $fiftyArray[count($fiftyArray)-1];

                   $secondlasttwohndArr = $twohundredArray[count($twohundredArray)-2];
                   $lasttwohndArray = $twohundredArray[count($twohundredArray)-1];
                   // echo "$secondlastffArr<$secondlasttwohndArr <br>";
                   if($secondlastffArr['first_ma']<$secondlasttwohndArr['second_ma'])
                   {
                       echo "50ma is below <br>";
                       
                       if($lastffArray['first_ma']>=$lasttwohndArray['second_ma'])
                       {
                           echo "50ma crossed 200ma <br>";
                           $SENDALERT = true;
                       }
                       else
                       {
                           echo "50ma NOT crossed 200ma <br>";
                       }
                   }

                   else if($secondlastffArr['first_ma']>$secondlasttwohndArr['second_ma'])
                   {
                       echo "200ma is below <br>";

                       if($lastffArray['first_ma']<=$lasttwohndArray['second_ma'])
                       {
                           echo "200ma crossed 50ma <br>";
                           $SENDALERT = true;
                       }
                       else
                       {
                           echo "200ma NOT crossed 50ma <br>";
                       }
                   }
                   else
                    {
                       echo "crossed on second last point<br>";
                    }

                    if($previous->time==$lastffArray['time'])
                    {
                       $SENDALERT = false;
                    }

                    // $SENDALERT = true;
                    if($SENDALERT)
                    {
                       $url = 'https://cryptoscannerpro.com/movingAverage/'.$symbol.'/'.$interval;
                       SmaController::screenshot($symbol,$interval,$url);//sending screen short
                       echo "screen short send<br>";
                    }
                    else
                    {
                       echo "screen short NOT send<br>";
                    }

                    $newfiftyArray = json_decode($previous->first_ma, true);
                    $newtwohundredArray = json_decode($previous->second_ma, true);

                    if($lastffArray['time']>end($newfiftyArray)['time'])
                    {
                       $newfiftyArray[] = $lastffArray;
                       // $newfiftyArray = array_unique(array_merge($fiftyArray,$newfiftyArray),SORT_REGULAR);
                       // $newfiftyArray = array_map("unserialize", array_unique(array_map("serialize", $newfiftyArray)));
                    }

                    if($lasttwohndArray['time']>end($newtwohundredArray)['time'])
                    {
                       $newtwohundredArray[] = $lasttwohndArray;
                    }
                   

                   echo "COUNT OF 50ma : ".count($newfiftyArray)."<br>";
                   echo "COUNT OF 200ma : ".count($newtwohundredArray)."<br>";

                    if(count($newfiftyArray)>$ttArrayCnt)
                    {
                       array_shift($newfiftyArray);
                    }
                    if(count($newtwohundredArray)>$ttArrayCnt)
                    {
                       array_shift($newtwohundredArray);
                    }

                    $previous->result              = json_encode($json_response);
                    $previous->screenshort_send    = 0;
                    $previous->first_ma            = json_encode($newfiftyArray);
                    $previous->second_ma       = json_encode($newtwohundredArray);
                    $previous->time                = $lastffArray['time'];
                    $previous->save();
                }
                else
                {
                    $newma = new Movingaverage;
                    $newma->symbol              = $symbol;
                    $newma->time_interval       = $interval;
                    $newma->result              = json_encode($json_response);
                    $newma->screenshort_send    = 0;
                    $newma->first_ma         = json_encode($fiftyArray);
                    $newma->second_ma    = json_encode($twohundredArray);
                    $newma->save();
                    echo "new symbol and interval saved<br>";
                }
               
               
               echo "------------------------------------<br>";

               // return $this->movingavarageChart($symbol,$interval);
           }
        }        
    }

    public function MaCandleSticks(Request $request)
    {
    	$symbol=$request->symbol;
    	$interval=$request->interval;

        $previous=Movingaverage::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first(); 

        $response = json_decode($previous->result);
        $first_ma = json_decode($previous->first_ma, true);
        $second_ma = json_decode($previous->second_ma, true);
        // print_r($first_ma);die;
        $data=[];
        $first_ma_points=[];
        $second_ma_points=[];
    	foreach ($response as $key => $value){
    		$data[$key][0]=floatval($value[6]);
    		$data[$key][1]=floatval($value[1]);
    		$data[$key][2]=floatval($value[2]);
    		$data[$key][3]=floatval($value[3]);
            $data[$key][4]=floatval($value[4]);
        }

        foreach ($first_ma as $first_ma_key => $first_ma_value) {
            $first_ma_points[$first_ma_key][0]=floatval($first_ma_value['time']);
            $first_ma_points[$first_ma_key][1]=floatval($first_ma_value['first_ma']);
        }

        foreach ($second_ma as $second_ma_key => $second_ma_value) {
            $second_ma_points[$second_ma_key][0]=floatval($second_ma_value['time']);
            $second_ma_points[$second_ma_key][1]=floatval($second_ma_value['second_ma']);
        }

        return array($data,$first_ma_points,$second_ma_points);
    }
    public function movingAverageChart($symbol,$interval)
    {
        return view('moving_average')->with('symbol',$symbol)->with('interval',$interval);
    }
    public static function screenshot($symbol,$interval,$url)
    {
        $image=public_path().'/telegram/ma.jpg';
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
          //$current_price=LowPrice::where('symbol',$symbol)->where('time_interval',$interval)->first();
          $alert_log->price='';
          $alert_log->price_2='';
          $alert_log->category=15; /*moving average*/
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
    }             
}      