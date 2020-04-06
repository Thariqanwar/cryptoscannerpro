<?php 
namespace App\Helpers;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\LowPrice;
use \App\Model\Telegram_chat;
use \App\Model\Cron_status;
use Auth;
use Session;
use DateTime;


class AppHelper
{
	  public static function find_low_price_old()
    {       
            $coins = file_get_contents('https://api.binance.com/api/v1/ticker/24hr');
            $coin_list = json_decode($coins);

            $coins=[];
            foreach ($coin_list as $key => $coin) 
            {
                if('BTC'==substr($coin->symbol, -3))
                {
                  $coins[]=$coin->symbol;
                }

            }
            $coins[]='BTCUSDT'; $coins[]='ETHUSDT'; 

                
            //$coins=['BTCUSDT','ETHUSDT','BNBUSDT','EOSUSDT'];
            $interval_list=['5m','1h','4h','6h','12h','1d'];
            foreach ($interval_list as $key => $interval) {
              foreach ($coins as $key => $symbol) 
              {
                 //$symbol="BTCUSDT";
                 //$interval="15m";
                 $limit=50;
               
                 $api_response = file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);
                 sleep(1);
                 $response = json_decode($api_response);
                 $rsi_array = AppHelper::calculateRSI($response);
                 //$rsi is an array(timestamp[],close[],rsi[])    
                 //dd($rsi);
                 $time  = $rsi_array[0];
                 $close = $rsi_array[1];
                
                 $rsi   = $rsi_array[2];
                  if( count($close) >0 )
                  {
                    $low_key = array_keys($close,min($close)); //return array with 1 value(key of lowest close value)
                  }
                  else
                  {
                    break;
                  }
                
                 $low_key = $low_key[0]; //key of lowest close value
                 $low_price      =   $close[$low_key];
                 $low_price_time =   $time[$low_key];
                 $rsi_value      =   $rsi[$low_key];
                 // dd($rsi_value);
                 $previous = LowPrice::where([ ['symbol',$symbol],['time_interval',$interval] ])->first();
                 if(empty($previous))
                  {
                     $save= new LowPrice;
                     $save->symbol=$symbol;
                     $save->time_interval=$interval;
                     $save->save();
                     $previous = LowPrice::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
                  }
                 if($previous->low_price_1 != NULL)
                  {
                      
                      if(($previous->low_price_1 != NULL) && ($previous->low_rsi_1 != NULL) )
                      {   
                          if( !in_array($previous->time_1, $time)) /*check previous low value stored in db is available in currently fetched api data    */
                          {    
                              $previous->low_price_1 = $previous->low_price_2;    
                              $previous->low_rsi_1   = $previous->low_rsi_2;    
                              $previous->time_1      = $previous->time_2;    
                              $previous->low_price_2 = $previous->low_price_3;   
                              $previous->low_rsi_2   = $previous->low_rsi_3;    
                              $previous->time_2      = $previous->time_3;    
                              $previous->low_price_3 = null;    
                              $previous->low_rsi_3   = null;    
                              $previous->time_3      = null;    
                              $previous->save();    
                              if( !in_array($previous->time_1, $time) )    
                              {           
                                 $previous->low_price_1 = $previous->low_price_2;    
                                 $previous->low_rsi_1   = $previous->low_rsi_2;    
                                 $previous->time_1      = $previous->time_2;    
                                 $previous->low_price_2 = null;    
                                 $previous->low_rsi_2   = null;    
                                 $previous->time_2      = null;    
                                 $previous->save();    
                              }    
                          }    
                         
                          if(($previous->low_price_1 != null)  && ($previous->low_rsi_1 !== null) )
                          {
                              if(($previous->low_price_2 == null)  && ($previous->low_rsi_2 == null))
                              {    
                                  if($low_price < $previous->low_price_1 )
                                  {   
                                      if( ($rsi_value >= 10) && ($rsi_value <= 40) )
                                      { 
                                        if($rsi_value > $previous->low_rsi_1 )
                                        {
                                          $previous->low_price_2=$low_price;
                                          $previous->time_2=$low_price_time;
                                          $previous->low_rsi_2= $rsi_value;
                                          $previous->save(); 
                                        }
                                        else
                                        {
                                          $previous->low_price_1=$low_price;
                                          $previous->time_1=$low_price_time;
                                          $previous->low_rsi_1= $rsi_value;
                                          $previous->save(); 
                                        }
                                      }
                                  } 
                              }                       
                          }                                         
                         
                          if(($previous->low_price_2 != null)  && ($previous->low_rsi_2 !== null) )
                          {
                              if($low_price < $previous->low_price_2 )
                              {    
                            
                                  if(($previous->low_price_3 == null)  && ($previous->low_rsi_3 == null))
                                  {
                                      if($rsi_value > $previous->low_rsi_2 )
                                      {
                                          if( ($rsi_value >= 10) && ($rsi_value <= 40) )
                                          {
                                            $candleStick_Count=file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit.'&startTime='.$previous->time_1.'&endTime='.$low_price_time);
                                            $candleStick_Count=json_decode($candleStick_Count);
                                            if(count($candleStick_Count) >= 5 )
                                            {
                                              if($rsi_value > $previous->low_rsi_1 )
                                              {
                                                /*reset first rsi point as per latest data.*/
                                                /*$price_key= array_search($previous->time_1,$rsi_array[0]);
                                                $new_rsi_1=$rsi_array[2][$price_key]; 
                                                $previous->low_rsi_1= $new_rsi_1;*/
                                                /*Reset rsi 1 end*/
                                                
                                                $previous->result= $api_response;
                                                
                                                $previous->low_price_3=$low_price;
                                                $previous->time_3=$low_price_time;
                                                $previous->low_rsi_3= $rsi_value;
                                                $previous->save();
                                               /* $timeset= date('H-m-s',time());
                                                 $txt="condition met:".$timeset."</b>";
                                                  $status=AppHelper::sendMessage($txt);
                                               */
                                                // check low high points between 2 low low points
                                                $low_high1=file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit.'&startTime='.$previous->time_1.'&endTime='.$previous->time_2);
                                                $low_high2=file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit.'&startTime='.$previous->time_2.'&endTime='.$previous->time_3);
                                                $low_high1=AppHelper::find_low_high($low_high1);
                                                $low_high2=AppHelper::find_low_high($low_high2);
                                                if($low_high1 > $low_high2)
                                                {
                                                  
                                                  $txt="Final Condition Met for" .$interval." <b>#".$symbol."</b>";
                                                  $status=AppHelper::sendMessage($txt);

                                                  /*$timeset= date('H-m-s',time());
                                                 $txt="screenshot send:".$timeset."</b>";
                                                  $status=AppHelper::sendMessage($txt);*/

                                                // $screenshot= AppHelper::screenshot($symbol,$interval);
                                                 /* $txt="M15 for <b>#".$symbol."</b>";
                                                  $status=AppHelper::sendMessage($txt);*/
                                                  
                                                }
                                               
                                              }     
                                                /*reset table row after sending screeenshot*/
                                                $previous->low_price_1=$previous->low_price_3;
                                                $previous->low_rsi_1=$previous->low_rsi_3;
                                                $previous->time_1=$previous->time_3;
                                                $previous->low_price_2=$previous->low_price_3=null;
                                                $previous->low_rsi_2=$previous->low_rsi_3=null;
                                                $previous->time_2=$previous->time_3=null;
                                                $previous->save(); 
                                            }    
                                          }
                                      }
                                  }
                              }                        
                          }         
                      }
                  }    
                  else
                  {
                      //dd( $low_price_time);
                      if( ($rsi_value >= 10) && ($rsi_value <= 40) )
                      {
                         $save=$previous;
                         $save->low_price_1=$low_price;
                         $save->time_1=$low_price_time;
                         $save->low_rsi_1= $rsi_value;
                         $save->time_interval=$interval;
                         $save->save();
                         //dd('first value saved');
                         //$txt="<b>Condition 1 : </b>RSI-1 <b>Between 10 & 40</b>  and <b>Lowest</b> Price for  <b>#".$symbol."</b>";
                         //$status=AppHelper::sendMessage($txt);
                      }      
                  }    
                 //dd('nothing new');
                 //return $data;           
              }
              //print_r($status); //foreach close
            }
    } //function close


    public static function find_low_high($low_high)
    {
        $response=json_decode($low_high);
        $data=[];
        foreach ($response as $key => $value)
        {
            $data[$key]=floatval($value[4]);     
        }
        return max($data); 
    }

    public static function sendMessage ($message, $reply = false, $mute = false, $inst_view = false,$keyboard = false) 
    {
      $botToken = env("TELEGRAM_API", "750434260:AAHuRAw0m3H9jbomD0riylrD2o9NpKBDJ-8");
      $chatId = '-1001496915788';
      $website = "https://api.telegram.org/bot".$botToken;
      $url = $website."/sendMessage?chat_id=".$chatId."&parse_mode=HTML&text=".urlencode($message);  
      if($reply){
        $url .= "&reply_to_message_id=$reply";
      }
      if($mute){
        $url .= "&disable_notification=1";
      } 
      if($inst_view){
        $url .= "&disable_web_page_preview=1";
      }            
      if($keyboard){
        $url .= "&reply_markup=".json_encode($keyboard);
      }
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);  
      return $data;
    }
        

    public static function screenshot($symbol,$interval)
    {
        /*$screen_shot_image = '';
        $use_cache = false;
        $apc_is_loaded = extension_loaded('apc');
        $url = 'http://cryptoscannerpro.com/'.$symbol;
        $localFile=$_SERVER['DOCUMENT_ROOT'].'/public/telegram/Image.jpeg';
        $localFiles=realpath($localFile);
        if($apc_is_loaded) 
        {
                apc_fetch("thumbnail:".$url, $use_cache);
        }
        if(!$use_cache) 
        {
            $screen_shot_json_data = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$url&screenshot=true&strategy=mobile");
            $screen_shot_result = json_decode($screen_shot_json_data, true);
            $screen_shot = $screen_shot_result['screenshot']['data'];
            if($apc_is_loaded) 
            {
                apc_add("thumbnail:".$url, $screen_shot, 2400);
            }
        }
        $screen_shot = str_replace(array('_','-'), array('/', '+'), $screen_shot);
        $ifp = fopen( $localFile, 'wb' ); 
        fwrite( $ifp, base64_decode( $screen_shot ) );
        fclose( $ifp ); 
        $chat_id    = '-1001496915788';
        $bot_url    = "https://api.telegram.org/bot750434260:AAHuRAw0m3H9jbomD0riylrD2o9NpKBDJ-8/";
        $urls       = $bot_url . "sendPhoto?chat_id=" . $chat_id;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); 
        $post_fields = array(
            'photo'     => new \CURLFile(realpath($localFile))
            
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $urls); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
        $output = curl_exec($ch);
       
        print_r($output);*/
         $image='/var/www/html/cryptonew/public/telegram/Image.jpg';
        //print_r($image);
         $url = 'http://cryptoscannerpro.com/symbol/'.$symbol.'/'.$interval;
        //$image = 'Images.jpeg';
        $accountKey = 'ee0f48';
        $key="eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tpZCI6IjE1NjMyNTM2NDg0MDctNSIsImVtYWlsIjoiY3J5cHRvc2Nhbm5lcnByb0BnbWFpbC5jb20iLCJzZXJ2aWNlIjoic2NyZWVuLnJpcCIsInBsYW4iOjEwMCwiaWF0IjoxNTYzMjUzNjQ4LCJleHAiOjE1OTQ3ODk2NDh9.MmPRgbb7mdC41AxPTmph89ALyLlqV8xEpeDnwvZ1o6YVg2MUlghoHZPD6usmpoUlNrpjQicNnzy5yEz1qiIQaxXlNYjaX_1TaWSJ3paNXLzroenuPtEZomqGbvPP6OUXstZ1Eq2JOUXuv-9qD0ieXIT9kvFkr0XazoNbY2sHbFON1vMU7wic0cybFVbFr-uFqh2ZncrKTtLwtyI9ZsOOJA3F9ElI-6kj9pJxafnL4mksVRsY7gBB7eYVs1G36_ExNg2mjBafaecXEjaoz3FK6kZFl65hZ7bForej4jDQfFJrhfUSceeMVzdOGqyybInO9Q9eLu1Xutmuc2k0bJyI1obASe1Ju2iRK2uYgGWluvpi9ZXDl5uSgFZhxh_FcX3fKGUFjBQeTx_rw7WLlZW2byXZ6WSTBwBLPJ38kc1HrNqekmxsx3feINHiEvpagyDOCvGuKNVLOh53IaZLmQQscgkgaEYhPuLrzlvDpdzrXQJcfgwS5x6gTKTereuJqZB81e68m4RrVQkYmx06LWw7nmhg8H_cg0ACC1kLc8HlSs0FAXGUxM0Uo8iyd1IETbqOdOkifrtpls0dXS_HuzSbX-dJcNRvVUFx5ZMp9CUu6AkBuh5EcR2rmmHqvJ9p6mkEc-5dhQURzWU-UPTffJsNvA4Cjma2PYvPBsUsbmhwJQg";
        
        //$ch = curl_init('https://screen.rip/capture?token='.$key.'&url='.$url.'&wait=5000');
        $ch = curl_init('http://api.screenshotmachine.com/?key=ee0f48&dimension=1024x786&delay=6000&cacheLimit=0&url='.$url);
        //print_r($ch);
        //$ch = curl_init('http://api.screenshotmachine.com/?key='.$accountKey.'&size=M&format=png&url='.$url);
        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $chat_id    = '-1001496915788';
        $bot_url    = "https://api.telegram.org/bot750434260:AAHuRAw0m3H9jbomD0riylrD2o9NpKBDJ-8/";
        $urls        = $bot_url . "sendPhoto?chat_id=" . $chat_id;
        
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
        $output = curl_exec($ch);
        print_r($output);
    }

    public static function calculateRSI_old($datas)
    {             
        $j = $k = 1; $count = ($datas)?count($datas):0;
        $period = 0.07142857; // 1/period  1/14    (RSI period)
          
        $close = $timestamp = $change = $gain = $loss = $avg_gain = $avg_loss = $rs = $rsi = array();     
        if($datas){ 
            foreach($datas as $key => $data){                             
        	    if(count($datas)< 15)
            	    break;
                $change[$key] = $datas[$j][4] - $data[4];      
                $gain[$key] = ($change[$key] > 0 )? $change[$key] : 0;
                $loss[$key] = ($change[$key] < 0 )? abs($change[$key]) : 0;     
                if($key == 13){
                    $avg_gain[0] = (array_sum($gain)/14);
                    $avg_loss[0] = (array_sum($loss)/14);
                    if($avg_loss[0] > 0){
                        $rs[0] = $avg_gain[0]/$avg_loss[0];
                    }else{
                        $rs[0] = 0;
                    }
                    if($avg_loss[0] > 0){
                        $rsi[0] = 100 - (100/(1+$rs[0])); //rsi value
                        $timestamp[$k]=$data[6]; //timestamp
                        $close[$k]=$data[4]; //close value
                    }else{
                        $rsi[0] = 100; //rsi value
                        $timestamp[$k]=$data[6]; //timestamp
                        $close[$k]=$data[4]; //close value
                    }
                } 
                if($key > 13){
                    $avg_gain[$k] = $gain[$key]*$period+(1-$period)*$avg_gain[$k-1];
                    $avg_loss[$k] = $loss[$key]*$period+(1-$period)*$avg_loss[$k-1];                    
                    if($avg_loss[$k] > 0){
                        $rs[$k] = $avg_gain[$k]/$avg_loss[$k];
                        $rsi[$k] = 100 - (100/(1+$rs[$k])); //rsi value
                        $timestamp[$k]=$data[6]; //timestamp
                        $close[$k]=$data[4]; //close value
                    }else{
                        $rsi[$k][0] = 100; //rsi value
                        $timestamp[$k]=$data[6]; //timestamp
                        $close[$k]=$data[4]; //close value
                    }
                    $k++;
                }
                if($j == ($count-1))
                    break;
                $j++; 
            }
        }
        //dd($timestamp);
        return array($timestamp,$close,$rsi);
         /*return array('time'=>$timestamp,'close'=>$close,'rsi'=>$rsi);*/
    }

  
   
    public static function setwebhook()
    {
      $botToken = env("TELEGRAM_API", "750434260:AAHuRAw0m3H9jbomD0riylrD2o9NpKBDJ-8");
      $website = "http://cryptoscannerpro.com/icoincrypto_commands";
      $url = "https://api.telegram.org/bot$botToken/setwebhook?url=$website";
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $data = curl_exec($ch);
      curl_close($ch);
    }

    public static function deletewebhook()
    {
      $botToken = env("TELEGRAM_API", "750434260:AAHuRAw0m3H9jbomD0riylrD2o9NpKBDJ-8");
      $url = "https://api.telegram.org/bot$botToken/setwebhook?url=";
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $data = curl_exec($ch);
      curl_close($ch);
    }

    public static function updateTelegramChatId($content)
    {
            $contents = json_decode($content, TRUE);
            
            $chatId = $contents['message']['chat']['id'];

            $exist=Telegram_chat::where('chat_id','=',"$chatId")->exists();
            if(!$exist){
                $obj=new Telegram_chat();
                $obj->chat_id="$chatId";
                if($contents['message']['chat']['type']=='private'){
                    $obj->chat_name=$contents['message']['chat']['first_name'];
                }else{
                    $obj->chat_name=$contents['message']['chat']['title'];
                }
                if(array_key_exists("username",$contents['message']['chat'])){
                    $obj->chat_user = '@'.$contents['message']['chat']["username"];
                }
                $obj->chat_type= $contents['message']['chat']['type'];
                $obj->chat_approve=1;                
                $obj->save();
            }else
            {
                if(array_key_exists("left_chat_participant",$contents["message"])){
                    $left_chat_id = $contents["message"]["left_chat_participant"]["id"];     
                    if($left_chat_id == '638669410'){
                        Telegram_chat::where('chat_id', "$chatId")                        
                            ->update(['chat_approve' => 0]);
                    }                    
                }
                if(array_key_exists("new_chat_participant",$contents["message"])){
                    $new_chat_id = $contents["message"]["new_chat_participant"]["id"];     
                    if($new_chat_id == '638669410'){
                        Telegram_chat::where('chat_id', "$chatId")                        
                            ->update(['chat_approve' => 1]);
                    }                    
                }
                if(array_key_exists("username",$contents['message']['chat'])){                    
                    Telegram_chat::where('chat_id', "$chatId")                        
                        ->update(['chat_user' => '@'.$contents['message']['chat']["username"]]);                                      
                }
            }           
            return;
    }
    public static function find_low_price()
    {
      $status=Cron_status::first();
      $status->status=0;
      $status->save();     
      //$coins = file_get_contents('https://api.binance.com/api/v1/ticker/24hr',false, $context);
      $curl_handle=curl_init();
      curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/ticker/24hr');
      curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
      curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
      $coins = curl_exec($curl_handle);
      curl_close($curl_handle);
      //print_r($coins);
      $coin_list = json_decode($coins);
      $coins=[];
      foreach ($coin_list as $key => $coin) 
      {
        if('BTC'==substr($coin->symbol, -3))
        {
          if('LRCBTC'!=$coin->symbol){
            $coins[]=$coin->symbol;
          }
          
        }
      }
      $coins[]='BTCUSDT'; $coins[]='ETHUSDT'; 
      //$coins=['BTCUSDT','ETHUSDT','BNBUSDT','EOSUSDT'];
      //$interval_list=['5m','15m','1h','4h','6h','12h','1d'];
      $interval_list=['15m'];
      foreach ($interval_list as $key => $interval) 
      {
        foreach ($coins as $key => $symbol) 
        {
          

        
          $limit=50;
          //$api_response = file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit,false, $context);
          $curl_handle=curl_init();
          curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);
          curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
          $api_response = curl_exec($curl_handle);
          curl_close($curl_handle);
          //sleep(1);
          $json_response = json_decode($api_response);
          $full_rsi=AppHelper::calculateRSI($json_response);
          unset($second_point);
          if(array_key_exists(35, $full_rsi['close']) && array_key_exists(35, $full_rsi['time']) && array_key_exists(35, $full_rsi['rsi']))
          {
            print_r($symbol."  ".$interval." 1  "); 
            if( min($full_rsi['close'])==$full_rsi['close'][35] )
            { print_r($symbol."  ".$interval."  2 "); 
              $min_price_1_rsi = $full_rsi['rsi'][35];
              if($min_price_1_rsi <=40) 
              { print_r($symbol."  ".$interval."  3 "); 
                $second_point=['time'=>$full_rsi['time'][35],'close'=>$full_rsi['close'][35],'rsi'=>$full_rsi['rsi'][35]];
                $second_point_key=35; 
              }
            } 
          }
          else
          {print_r($symbol."  ".$interval."  4 "); 
            continue ;
          }
          if((isset($second_point)) && (!isset($first_point)))
          {  print_r($symbol."  ".$interval."  5 "); 
            for ($i=34; $i > 0 ; $i--) 
            {  
              if(($full_rsi['close'][$i] > $second_point['close']) && ($full_rsi['rsi'][$i] < $second_point['rsi']) && ($full_rsi['rsi'][$i] <= 20) )
              {print_r($symbol."  ".$interval."  6 ");  
                $first_point=['time'=>$full_rsi['time'][$i],'close'=>$full_rsi['close'][$i],'rsi'=>$full_rsi['rsi'][$i]];
                $first_point_key=$i;
                $diff=$second_point_key - $first_point_key; /*Number of candle sticks between to points*/
                $rsi_set=array_slice($full_rsi['rsi'], $first_point_key,$diff);
                if(count(array_slice($full_rsi['close'], $first_point_key,$diff)) >=7)
                {print_r($symbol."  ".$interval."  7 "); 
                  $previous=LowPrice::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
                  if(!empty($previous))
                  {print_r($symbol."  ".$interval."  8 "); 
                    $first=strtotime($previous->created_at);
                    $now= strtotime(date('Y-m-d H:m:s'));
                    $tinterval  = abs( $first-$now);
                    $minutes   = round($tinterval / 60);
                    if($previous->time_2 <= $first_point['time'] )
                    {print_r($symbol."  ".$interval."  9 "); 
                      
                      if(min($rsi_set) >= $first_point['rsi']) /*if rsi less than first point rsi it will not take screenshot*/
                      {
                        print_r($symbol."  ".$interval." 10  ");  
                        $rsi_down=0; 
                        for ($i=$first_point_key+1; $i < $second_point_key ; $i++) 
                        { 
                          $ax=$full_rsi['time'][$first_point_key];
                          $ay=$full_rsi['rsi'][$first_point_key];
                          $bx=$full_rsi['time'][$second_point_key];
                          $by=$full_rsi['rsi'][$second_point_key];
                          $cx=$full_rsi['time'][$i];
                          $cy=$full_rsi['rsi'][$i];/*32.448970985985*/;
                          $r=(($bx - $ax)*($cy - $ay) - ($by - $ay)*($cx - $ax));  
                          if($r < 0)
                          {print_r($symbol."  ".$interval."  11 "); 
                            $rsi_down=1; /*rsi goes down to line*/
                          }
                        }
                        $price_down=0; 
                        for ($i=$first_point_key+1; $i < $second_point_key ; $i++) 
                        { 
                          $ax=$full_rsi['time'][$first_point_key];
                          $ay=$full_rsi['close'][$first_point_key];
                          $bx=$full_rsi['time'][$second_point_key];
                          $by=$full_rsi['close'][$second_point_key];
                          $cx=$full_rsi['time'][$i];
                          $cy=$full_rsi['close'][$i];/*32.448970985985*/;
                          $r=(($bx - $ax)*($cy - $ay) - ($by - $ay)*($cx - $ax));  
                          if($r < 0)
                          {print_r($symbol."  ".$interval."  12 "); 
                            $price_down=1; /*rsi goes down to line*/
                          }
                        }
                        if(($rsi_down==0) && ($price_down==0))
                        {print_r($symbol."  ".$interval."  13 "); 
                          $per_difference=$second_point['close']-$first_point['close'];
                          $per=$per_difference/$second_point['close'] * 100;
                          if($per >= 2)
                          {print_r($symbol."  ".$interval."  14 "); 
                            $previous->symbol =$symbol;
                            $previous->time_interval =$interval;

                            $previous->low_price_ =$first_point['close'];
                            $previous->low_rsi_ =$first_point['rsi'];
                            $previous->time_1 =$first_point['time'];

                            $previous->low_price_ = $second_point['close'];
                            $previous->low_rsi_ = $second_point['rsi'];
                            $previous->time_2 = $second_point['time'];
                            $previous->result = $api_response;
                            $previous->created_at = date("Y-m-d H:m:s");
                            if($previous->save())
                            {print_r($symbol."  ".$interval."  15 "); 
                              //$txt=" #New Final Condition Met for" .$interval." <b>#".$symbol."</b>";
                              //$status=AppHelper::sendMessage($txt);
                              $screenshot= AppHelper::screenshot($symbol,$interval);
                              break 2;  /*break the loop and go to next coin*/
                            } 
                          }
                        }
                      }
                    }    
                  }
                  else
                  {print_r($symbol."  ".$interval."  16 "); 
                    if(min($rsi_set) >= $first_point['rsi']) /*if rsi less than first point rsi it will not take screenshot*/
                    {print_r($symbol."  ".$interval."  17 "); 
                      $rsi_down=0; 
                      for ($i=$first_point_key+1; $i < $second_point_key ; $i++)  /*loop to check rsi go down*/
                      { 
                        $ax=$full_rsi['time'][$first_point_key];
                        $ay=$full_rsi['rsi'][$first_point_key];
                        $bx=$full_rsi['time'][$second_point_key];
                        $by=$full_rsi['rsi'][$second_point_key];
                        $cx=$full_rsi['time'][$i];
                        $cy=$full_rsi['rsi'][$i];/*32.448970985985*/;
                        $r=(($bx - $ax)*($cy - $ay) - ($by - $ay)*($cx - $ax));  
                        if($r < 0)
                        {print_r($symbol."  ".$interval."  18 "); 
                          $rsi_down=1; /*rsi goes down to line*/
                        }
                      }
                      $price_down=0; 
                      for ($i=$first_point_key+1; $i < $second_point_key ; $i++) /*loop to check price go down*/
                      { 
                        $ax=$full_rsi['time'][$first_point_key];
                        $ay=$full_rsi['close'][$first_point_key];
                        $bx=$full_rsi['time'][$second_point_key];
                        $by=$full_rsi['close'][$second_point_key];
                        $cx=$full_rsi['time'][$i];
                        $cy=$full_rsi['close'][$i];/*32.448970985985*/;
                        $r=(($bx - $ax)*($cy - $ay) - ($by - $ay)*($cx - $ax));  
                        if($r < 0)
                        {print_r($symbol."  ".$interval."  19 "); 
                          $price_down=1; /*rsi goes down to line*/
                        }
                      }
                      if(($rsi_down==0) && ($price_down==0)) /*if lines not breach rsi and price */
                      {print_r($symbol."  ".$interval."  20 "); 
                        $per_difference=$second_point['close']-$first_point['close'];
                        $per=$per_difference/$second_point['close'] * 100;
                        if($per >= 2)
                        {print_r($symbol."  ".$interval."  21 "); 
                          $set = new LowPrice;
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
                          if($set->save())
                          {print_r($symbol."  ".$interval."  22 "); 
                           //$txt=" #New Final Condition Met for" .$interval." <b>#".$symbol."</b>";
                           //$status=AppHelper::sendMessage($txt);
                            $screenshot= AppHelper::screenshot($symbol,$interval);
                            break 2;  /*break the loop and go to next coin*/
                          } 
                        }  
                      }  
                    }  
                  }
                }
                else
                {print_r($symbol."  ".$interval."  23 "); 
                  unset($first_point);
                  continue;
                }  
              }
            }   
          }
        }//print_r($status); //foreach close  
      } 
      $status=Cron_status::first();
      $status->status=1;
      $status->save(); 
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
}  
