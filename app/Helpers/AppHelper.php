<?php 
namespace App\Helpers;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\LowPrice;
use App\Model\Telegram_chat;
use App\TimeFrame;
use Auth;
use Session;
use DateTime;
use App\User;
use App\AlertLog;
use App\HrDivergence;


class AppHelper
{
   
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

    public static function sendMessage ($chat_id,$message, $reply = false, $mute = false, $inst_view = false,$keyboard = false) 
    {
      $botToken = env("TELEGRAM_API", "743896776:AAFC2nou6yKHom3-trRtdYar_7mEU3iFTE8");
      $chatId = /*'573420013'*/$chat_id;
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
        
    public static function sendMessage_one_one ($message,$id,$token) 
    {
      $botToken = $token;
      $chatId = $id;
      $website = "https://api.telegram.org/bot".$botToken;
      $url = $website."/sendMessage?chat_id=".$chatId."&parse_mode=HTML&text=".urlencode($message);  
      //print_r($url);die;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);  
      return $data;
    }

    public static function screenshot($symbol,$interval)
    {
        
       
        $image=public_path().'/telegram/Image.jpg';
        //print_r($image);
         $url = 'https://cryptoscannerpro.com/symbol/'.$symbol.'/'.$interval;
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
          $alert_log->price=$current_price->low_price_1;
          $alert_log->price_2=$current_price->low_price_2;
          $alert_log->category=1; /*rsi*/
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

        
        //print_r($output);
    }
    public static function hrscreenshot($symbol,$interval,$url)
    {
        
       
         $image=public_path().'/telegram/HrD.jpg';
        //print_r($image);
        $url = 'https://cryptoscannerpro.com/symbol/'.$symbol.'/'.$interval;
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
          $alert_log->price=$current_price->low_price_1;
          $alert_log->price_2=$current_price->low_price_2;
          $alert_log->category=5; /*Horizontal support*/
          $alert_log->save();
          $filename=$alert_log->id;
          $target=url('/').'/public/telegram/'.$filename.'.jpg';
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
    
    


    public static function oneHourScreenshot($symbol,$interval)
    {
      $image=public_path().'/telegram/Image.jpg';

      $url = 'https://cryptoscannerpro.com/onehoursymbol/'.$symbol.'/'.$interval;

        //$ch = curl_init('https://screen.rip/capture?token='.$key.'&url='.$url.'&wait=5000');
        $ch = curl_init('http://api.screenshotmachine.com/?key=ee0f48&dimension=1024x786&delay=8000&cacheLimit=0&url='.$url);

        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $chat_id    = '-1001410227642';
        $bot_url    = "https://api.telegram.org/bot968857074:AAGqyUbt2lDwmSd9GovANGnaDPPVGKn_QzE/";
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

        // print_r($output);
    }

    
    public static function setwebhook()
    {
      $botToken = env("TELEGRAM_API", "743896776:AAFC2nou6yKHom3-trRtdYar_7mEU3iFTE8");
      $website = "https://cryptoscannerpro.com/bot_commands";
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
      $botToken = env("TELEGRAM_API", "743896776:AAFC2nou6yKHom3-trRtdYar_7mEU3iFTE8");
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
        
            $curl_handle=curl_init();
            curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/ticker/24hr');
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
            $coins = curl_exec($curl_handle);
            curl_close($curl_handle);
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
      $interval_list=TimeFrame::pluck('short')->toArray();
      //$interval_list=['15m','1h','4h','6h','12h','1d'];
      foreach ($interval_list as $key => $interval) 
      {
        foreach ($coins as $key => $symbol) 
        {
         
          $limit=50;
          //$api_response = file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit,false, $context);
          //sleep(1);
          $curl_handle=curl_init();
          curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);
          curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
          $api_response = curl_exec($curl_handle);
          curl_close($curl_handle);
          $json_response = json_decode($api_response);
         
          
          $full_rsi=AppHelper::calculateRSI($json_response);
          
          unset($second_point);
          if(array_key_exists(35, $full_rsi['close']) && array_key_exists(35, $full_rsi['time']) && array_key_exists(35, $full_rsi['rsi']))
          {
            if( min($full_rsi['close'])==$full_rsi['close'][35] )
            {
              $min_price_1_rsi = $full_rsi['rsi'][35];
              if($min_price_1_rsi <=40) 
              {
                $second_point=['time'=>$full_rsi['time'][35],'close'=>$full_rsi['close'][35],'rsi'=>$full_rsi['rsi'][35]];
                $second_point_key=35; 
               
              }
            } 
          }
          else
          {
            continue ;
          }
          if((isset($second_point)) && (!isset($first_point)))
          {  
            for ($i=34; $i > 0 ; $i--) 
            { 
              if(($full_rsi['close'][$i] > $second_point['close']) && ($full_rsi['rsi'][$i] < $second_point['rsi']) && ($full_rsi['rsi'][$i] <= 30) )
              {
                $first_point=['time'=>$full_rsi['time'][$i],'close'=>$full_rsi['close'][$i],'rsi'=>$full_rsi['rsi'][$i]];
                $first_point_key=$i;
                $diff=$second_point_key - $first_point_key; /*Number of candle sticks between to points*/
                $rsi_set=array_slice($full_rsi['rsi'], $first_point_key,$diff);
                if(count(array_slice($full_rsi['close'], $first_point_key,$diff)) >=7)
                {
                  $previous=LowPrice::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
                  if(!empty($previous))
                  {
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
                          //$txt=" #New Final Condition Met for" .$interval." <b>#".$symbol."</b>";
                          //$status=AppHelper::sendMessage($txt);
                          $screenshot= AppHelper::screenshot($symbol,$interval);
                          if($interval=='1h'){
                            AppHelper::oneHourScreenshot($symbol,$interval);
                          }
                          unset($previous);
                          break 2;  /*break the loop and go to next coin*/
                        } 
                      }
                    }    
                  }
                  else
                  {
                    
                    if(min($rsi_set) >= $first_point['rsi']) /*if rsi less than first point rsi it will not take screenshot*/
                    {
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
                      {
                       //$txt=" #New Final Condition Met for" .$interval." <b>#".$symbol."</b>";
                       //$status=AppHelper::sendMessage($txt);
                        $screenshot= AppHelper::screenshot($symbol,$interval);
                        if($interval=='1h'){
                          AppHelper::oneHourScreenshot($symbol,$interval);
                        }
                         unset($previous);
                         unset($set);
                        break 2;  /*break the loop and go to next coin*/
                      } 
                    }  
                  }
                }
                else
                {
                  unset($previous);  
                  unset($first_point);
                  continue;
                }  
              }
            }   
          }
        }//print_r($status); //foreach close  
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
    public static function getTelegramId(){
        $Token="743896776:AAE_I7Et1wF9rpwVN0doCLJkUH1iBOipbzc";
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,'https://api.telegram.org/bot'.$Token.'/getUpdates');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);

        $subscribers = json_decode($response);
        if(count($subscribers->result)!=0){
          foreach ($subscribers->result as $key => $value) {
           // print_r($value->message);
            $user=User::where('password',$value->message->text)->where('telegram_id',NULL)->first();
            //print_r($value->message->chat);
            if($user){

                $user->telegram_id=$value->message->chat->id;
                $user->telegram_username=$value->message->chat->first_name;
                $user->status=1;
                $user->save();
                $txt="Hi ".$value->message->chat->first_name.", Your account verified successfully";
                $send=AppHelper::sendMessage_one_one($txt,$value->message->chat->id,$Token);
                print_r($send);

            }
          }
        }
        
    }
    public static function hrDivergence(){
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

        $interval_list=['15m'];
        foreach ($interval_list as $key => $interval){
            foreach ($coins as $key => $symbol){

                // if($key==1){
                //     exit();
                // }
               
                $limit=301;
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
                array_pop($json_response);


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
                    echo "There is no hiting point<br>";
                    continue;
                }
                $cmnClose = $close1[2]; // take the third value from sorted array which will assume as the point which hits 4 times

                $cmnCloseKey = array_search($cmnClose,$close);
                $time = $closetime[$cmnCloseKey]; // take the time of the point which hits 4 times
                
                $boxArea = (max($low)-$minlow)/16; //calculate the highlight box area 
                $boxWidth = (max($closetime)-min($closetime))/100;// calcluate extra covarge area for highlight box in time 

                /////////////////////////////////Third Logic /////////////////////////

                $previous=HrDivergence::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first(); 

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
                    $avgslicedpeak = (count($slicedpeak)>0)?array_sum($slicedpeak)/count($slicedpeak):0;
                    
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
                        // $previous->low_3  =  '';
                        // $previous->time_4 =  '';
                        // $previous->low_4  =  '';
                        $previous->result =  json_encode($json_response);
                        $previous->screenshort_send = 1;
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
                        $previous->low_3  =  $previous->minclose + $boxArea;
                        $previous->time_4 =  $lastLowTime+$boxWidth;
                        $previous->low_4  =  $previous->minclose - $boxArea;
                        $previous->result =  json_encode($json_response);
                        $previous->screenshort_send = 1;
                        $previous->save();

                        $url = 'https://cryptoscannerpro.com/hrDivergence/'.$symbol.'/'.$interval;
                        AppHelper::hrscreenshot($symbol,$interval,$url);//sending screen short
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
                    $newSave = new HrDivergence;
                    $newSave->symbol        =  $symbol;
                    $newSave->time_interval =  $interval;
                    $newSave->minclose      =  $cmnClose;
                    $newSave->lastlow =  '';
                    $newSave->time_1 =  $time-$boxWidth;
                    $newSave->low_1  =  $cmnClose-$boxArea;
                    $newSave->time_2 =  $time-$boxWidth;
                    $newSave->low_2  =  $cmnClose+$boxArea;
                    $newSave->time_3 =  '';
                    $newSave->low_3  =  '';
                    $newSave->time_4 =  '';
                    $newSave->low_4  =  '';
                    $newSave->result =  json_encode($json_response);
                    $newSave->screenshort_send = 0;
                    $newSave->save();
                    echo "new symbol and interval saved<br>";
                }

                echo "--------------------------------------------------<br><br>";

            }
        }

        // return $coins;

    }
     public function CandleSticks(Request $request){
      $symbol=$request->symbol;
      $interval=$request->interval;
        
        $previous=HrDivergence::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
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
      foreach ($response as $key => $value){
        $data[$key][0]=floatval($value[6]);
        $data[$key][1]=floatval($value[1]);
        $data[$key][2]=floatval($value[2]);
        $data[$key][3]=floatval($value[3]);
        $data[$key][4]=floatval($value[4]);
        }
        return array($data,$points);
    }
}  
