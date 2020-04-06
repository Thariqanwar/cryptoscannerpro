<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
//use \App\Model\Telegram_chat;
use Ixudra\Curl\Facades\Curl;
use \App\Model\Price_alert;
use \App\Model\Events;
use \App\Model\Pivot;
use \App\Model\Coins;
use \App\Model\Icos;
use \App\Model\Feedback;
use \App\Hrdivergence;
use \App\Movingaverage;
use \App\Binance;
use Carbon\Carbon;
use Shortener;
use Twitter;
use Helper;
use Mail;
use App\PaymentDetails;
use App\Helpers\coinpayments\src\CoinpaymentsAPI;
use Auth;
use App\User;
// use Ixudra\Curl\Facades\Curl;


class CommonController extends Controller{

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

    // -1001410227642
    public static function sendMessage ($chat_id,$message, $reply = false, $mute = false, $inst_view = false,$keyboard = false) 
    {
      $botToken = env("TELEGRAM_API", "963741278:AAFVzAoTodNIApgKxu-qXbxcvbI6Z4YSLHo");
      $chatId = /*'573420013'*/$chat_id;
      $website = "https://api.telegram.org/bot".$botToken;
      $url = $website."/sendMessage?chat_id=".$chatId."&parse_mode=HTML&text=".urlencode($message);  
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);  
      return $data;
    }

    public static function  testsendScreenshot($url,$image){
        $image='/home/cspxxvk1/public_html/public/telegram/'.$image;

        // $url = 'http://cryptoscannerpro.com/onehoursymbol/'.$symbol.'/'.$interval;
        // $url = 'http://cryptoscannerpro.com/hrDivergenceChart/'.$symbol.'/'.$interval;
        $secret = "Konya";
        $hash = md5($url.$secret);

        // $ch = curl_init('http://api.screenshotmachine.com/?key=988ac0&device=tablet&dimension=1280x800&delay=8000&cacheLimit=0&url='.$url);//bigchief
        // $ch = curl_init('http://api.screenshotmachine.com/?key=821e75&device=tablet&dimension=1280x800&delay=8000&cacheLimit=0&url='.$url);//sreyas
        // $ch = curl_init('http://api.screenshotmachine.com/?key=db92cb&device=tablet&dimension=1280x800&delay=8000&cacheLimit=0&url='.$url);//Rahul
        $ch = curl_init('http://api.screenshotmachine.com/?key=cbae08&dimension=1024xfull&delay=8000&cacheLimit=0&url='.$url.'&hash='.$hash);//bigchief client
        
        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        // $chat_id    = '-1001410227642';//Big chief 
        $chat_id    = '-1001342307796';//test big chief
        $bot_url    = "https://api.telegram.org/bot963741278:AAFVzAoTodNIApgKxu-qXbxcvbI6Z4YSLHo/";
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
        echo "send";
        // print_r($output);
    }

    public static function  sendScreenshot($url,$image){
        $image='/home/cspxxvk1/public_html/public/telegram/'.$image;
        $secret = "Konya";
        $hash = md5($url.$secret);
        // $ch = curl_init('http://api.screenshotmachine.com/?key=cbae08&device=tablet&dimension=1280x800&delay=8000&cacheLimit=0&url='.$url.'&hash='.$hash);//bigchief client
        $ch = curl_init('http://api.screenshotmachine.com/?key=cbae08&dimension=1024xfull&delay=8000&cacheLimit=0&url='.$url.'&hash='.$hash);//bigchief client

        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $chat_id    = '-1001410227642';//Big chief 
        // $chat_id    = '-1001342307796';//test big chief
        $bot_url    = "https://api.telegram.org/bot963741278:AAFVzAoTodNIApgKxu-qXbxcvbI6Z4YSLHo/";
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
        echo "send";
        // print_r($output);
    }

    public static function binanceApicall(){
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/ticker/24hr');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'bigchief');
        $coins = curl_exec($curl_handle);
        curl_close($curl_handle);

        // echo $coins."<br>";die;
        $coin_list = json_decode($coins);
        $coins=[];

        foreach ($coin_list as $key => $coin){ 
            if('BTC'==substr($coin->symbol, -3) || $coin->symbol=='BTCUSDT' || $coin->symbol=='ETHUSDT'){
                // echo $coin->symbol." => ".$coin->quoteVolume."<br>";
                if($coin->quoteVolume>100){
                    $coins[]=['symbol'=>$coin->symbol,'quoteVolume'=>$coin->quoteVolume];
                }
            }
        }

        $interval_list=['1m','15m', '1h'];
        $limit_list = [300];
        foreach($limit_list as $limitkey =>$limit){
            foreach ($interval_list as $key => $interval){
                foreach ($coins as $key => $symbolDet){

                    $symbol = $symbolDet['symbol']; 
                    echo "<br>$key---$interval---$symbol----$limit---<br>";

                    ///////// Take Current values for comparing and plot graph////////////////
                    $curl_handle=curl_init();
                    curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);
                    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
                    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'bigchief');
                    $api_response = curl_exec($curl_handle);
                    curl_close($curl_handle);
                    
                    $previous=Binance::where([ ['symbol','=',$symbol],['candle_interval','=',$interval], ['candle_limit','=',$limit] ])->first();
                    if(!empty($previous)){
                        $previous->datas     = $api_response;
                        $previous->quoteVolume = $symbolDet['quoteVolume'];
                        $previous->save();
                    }else{
                        $newdata = new Binance;
                        $newdata->candle_limit      = $limit;
                        $newdata->candle_interval   = $interval;
                        $newdata->symbol    = $symbol;
                        $newdata->datas     = $api_response;
                        $newdata->quoteVolume = $symbolDet['quoteVolume'];
                        $newdata->save();
                    }
                }
            }
        }


    }

    public static function findPaymentStatus()
    {
        $pendings = PaymentDetails::where(['payment_status'=> 0])->get();
        $private_key = '3a76A8eBf75c2034c58BB0dd2A914d24d3354D3367eCd597277AF58a2a419e89';
        $public_key = 'a873b14b4f33c9d0571f3b685e10fd5bdbbf51a4b4fd002e09a626c717208e0d';
        $cps_api = new CoinpaymentsAPI($private_key, $public_key, 'json');

        foreach($pendings as $detail)
        {
            $status = $cps_api->GetTxInfoSingle($detail->txn_id);
            $data = PaymentDetails::find($detail->id);

            if($status['error'] == 'ok')
            {
                $data->payment_status = $status['result']['status'];
                $data->save();
            }
        }

        $completed = PaymentDetails::where(['payment_status'=> 100,'status' => 0])->get();

        foreach($completed as $detail)
        {   
            $user = User::find($detail->user_id);
            $user->subscription_type = $detail->subscription_type;
            $user->subscription_start= date("Y-m-d h:i:sa");
            $user->subscription_end= date("Y-m-d h:i:sa", strtotime('+'.$detail->period->text));
            $user->save();
             $detail->status = 1;
            if($detail->save()) 
            {
                $data = array('type'=>$detail->subscription_type,'amount'=>$detail->amount);
                $to=$detail->email;
                Mail::send(['text'=>'mail'], $data, function($message) use($to) {
                    $message->to($to, 'Tutorials Point')->subject('Subscription  Details');
                    $message->from('_mainaccount@cryptoscannerpro.com','cryptoscannerpro');
                });
            }
        }
    }

}

