<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use \App\Hrdivergence;
use \App\Binance;
use \App\Rsi;
use App\LowPrice;

use \App\Http\Controllers\CommonController;

// use Ixudra\Curl\Facades\Curl;


class RsiPrediction extends Controller{

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }

    public static function sendMessage ($chatId,$message, $reply = false, $mute = false, $inst_view = false,$keyboard = false) {
        $botToken = env("TELEGRAM_API", "912792324:AAHqBvqotzn0OkO-xbxAkGbRZxk6JxCZ9_w");//bot3 token
        // $chatId = $chat_id;
        $website = "https://api.telegram.org/bot".$botToken;
        $url = $website."/sendMessage?chat_id=".$chatId."&parse_mode=HTML&text=".urlencode($message);  

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);  
        return $data;
    }

    public static function sendScreenshot($url,$image){
        $image='/home/cspxxvk1/public_html/public/telegram/'.$image;

        $secret = "Konya";
        $hash = md5($url.$secret);
        $ch = curl_init('http://api.screenshotmachine.com/?key=cbae08&dimension=1024xfull&delay=8000&cacheLimit=0&url='.$url.'&hash='.$hash);//bigchief client
        
        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        // $chat_id    = '-390738766';//bot3old
        $chat_id    = '-1001290342700';//bot3old
        $bot_url    = "https://api.telegram.org/bot912792324:AAHqBvqotzn0OkO-xbxAkGbRZxk6JxCZ9_w/";//bot3 token
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

    public function rsipredChart($symbol,$interval){
        return view('bigchief/rsipredictionChart')->with('symbol',$symbol)->with('interval',$interval);

        // $text = "Test chat from bot";
        // $chat_id    = '-390738766';//bot3
        // return $this->sendMessage($chat_id, $text);//sending text message
    }

    public static function rsiPrediction($symbol,$interval){
        $url = 'http://cryptoscannerpro.com/rsipredChart/'.$symbol.'/'.$interval;
        RsiPrediction::sendScreenshot($url,"rsiprediction.jpg");//sending screen short

        $priceDet = RsiPrediction::getPricedet($symbol, $interval);
        $currentPrice = rtrim(sprintf('%.8f', $priceDet['currentPrice']),0);
        $profit = rtrim(sprintf('%.8f', $priceDet['profit']),0);
        $text = "-  #$symbol ($interval)  -".chr(10);
        $text.= "Current Price : $currentPrice".chr(10);
        $text.= "Take profit : $profit".chr(10);

        // $chat_id    = '-390738766';//bot3old
        $chat_id    = '-1001290342700';//bot3
        return RsiPrediction::sendMessage($chat_id, $text);//sending text message
    }

    public static function getPricedet($symbol, $interval){
        $saved_result=Rsi::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
    	$response=$saved_result->result; /*file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);*/
        $response=json_decode($response);

        $low_price_1 = $saved_result->low_price_1;
        $openArray = [];
        $lowArray = [];
        $timeArray = [];
    	foreach ($response as $key => $value){
            $openArray[] = floatval($value[1]);
            $lowArray[] = floatval($value[4]);//close price
            $timeArray[] = floatval($value[6]);
        }

        $array_keys = array_keys($lowArray,$low_price_1);
        $arrayKey = max($array_keys);

        $rev_openArray = array_reverse($openArray);
        $rev_openArray = array_slice($rev_openArray, (count($openArray)-$arrayKey-1) );

        $peakVal = 0;
        $skcount = 0;
        foreach($rev_openArray as $rkey => $rvalue) {
            if($rvalue>$peakVal){ $peakVal=$rvalue; $skcount=0;}
            else if($skcount<=20){ $skcount++; }
            else{ break; }
        }

        $priceDet['currentPrice'] = $lowArray[count($lowArray)-1];
        $priceDet['profit'] = $peakVal;

        return $priceDet;
    }


    public function CandleSticks(Request $request)
    {
    	$symbol=$request->symbol;
    	$interval=$request->interval;
    	$startTime=1561420800;
    	$endTime=1561507200;
    	$limit=50;
    	
        // $saved_result=LowPrice::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
        $saved_result=Rsi::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
        // dd($saved_result);
    	$response=$saved_result->result; /*file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);*/
        $response=json_decode($response);

        $data=[];
        $low_price_1 = $saved_result->low_price_1;
        $low_price_2 = $saved_result->low_price_2;
        $time_1 = $saved_result->time_2;
        $openArray = [];
        $lowArray = [];
        $timeArray = [];
    	foreach ($response as $key => $value) 
    	{
    		$data[$key][0]=floatval($value[6]); 
    		$data[$key][1]=floatval($value[1]);
    		$data[$key][2]=floatval($value[2]);
    		$data[$key][3]=floatval($value[3]);
            $data[$key][4]=floatval($value[4]);
            
            $openArray[] = floatval($value[1]);
            $lowArray[] = floatval($value[4]);//close price
            $timeArray[] = floatval($value[6]);
        }

        $array_keys = array_keys($lowArray,$low_price_1);
        $arrayKey = max($array_keys);

        $rev_openArray = array_reverse($openArray);
        $rev_openArray = array_slice($rev_openArray, (count($openArray)-$arrayKey-1) );

        $peakVal = 0;
        $skcount = 0;
        foreach($rev_openArray as $rkey => $rvalue) {
            if($rvalue>$peakVal){ $peakVal=$rvalue; $skcount=0;}
            else if($skcount<=20){ $skcount++; }
            else{ break; }
        }
        
        $balankCandles = 45;
        $points=Rsi::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
        $timeDiff = $timeArray[1] - $timeArray[0];
        $peakDiff = ($peakVal-$low_price_2)/$balankCandles;

        for($i=0;$i<=$balankCandles;$i++){
            $slPoint[$i][0] = $time_1 + ($timeDiff*$i);
            $slPoint[$i][1] = $low_price_2 + ($peakDiff*$i);
        }

        // print_r($slPoint[count($slPoint)-1]);

        $boxwidth = (max($openArray) - min($lowArray))/12;

        $lastTimePoint = $slPoint[count($slPoint)-1][0];
        $lastPeakPoint = $slPoint[count($slPoint)-1][1];

        // echo "$peakVal - $lastPeakPoint";die;

        $boxTimePoint1 = $lastTimePoint - ($timeDiff*15);
        $boxTimePoint2 = $lastTimePoint + ($timeDiff*15);
        $boxPeakPoint1 = $lastPeakPoint + ($boxwidth);
        $boxPeakPoint2 = $lastPeakPoint - ($boxwidth);

        $boxPoints[0] = [$boxTimePoint1,$boxPeakPoint1];
        $boxPoints[1] = [$boxTimePoint2,$boxPeakPoint1];
        $boxPoints[2] = [$boxTimePoint2,$boxPeakPoint2];
        $boxPoints[3] = [$boxTimePoint1,$boxPeakPoint2];

        for($j=0;$j<10;$j++){
            $linePoint[$j] = [$boxTimePoint1+($boxTimePoint2-$boxTimePoint1)*$j/10,$boxPeakPoint2];
        }

        return array($data,$points, $slPoint, $boxPoints, $linePoint);
    }


}

