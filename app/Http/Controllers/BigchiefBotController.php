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
use Carbon\Carbon;
use Shortener;
use Twitter;
use Helper;

use \App\Http\Controllers\CommonController;
use \App\Binance;

// use Ixudra\Curl\Facades\Curl;


class BigchiefBotController extends Controller{

    public function __construct()    {
        date_default_timezone_set('Asia/Calcutta');
    }


    //Bot Commands
    public function bot_commands(Request $request){
        $content = file_get_contents('php://input');
        $contents = json_decode($content, TRUE);
        //        $authorize = fopen("./storage/logs/bot.txt", "a+") or die("Unable to open file!");
        //        $arb_data = $content. chr(10);
        //        fwrite($authorize, $arb_data);
        //        fclose($authorize);

        if(!empty($contents["message"])){
            // AppHelper::updateTelegramChatId($content);
            $chatId = $contents["message"]["chat"]["id"];
            if(array_key_exists('text',$contents["message"])){
                $command_text = ($contents["message"]["text"]) ? $contents["message"]["text"]:'';
                $message = explode("@",$command_text);
                $bot_command = explode(" ",$message[0]);

                $interval_list=['1m','3m','5m','15m','30m','1h','2h','4h','6h','12h','1d','1w','1M'];

                switch(strtolower($bot_command[0])) {
                    case "/start":
                        $text = 'Hello!'.chr(10);
                        $text = 'Hello!  I am the official Big chief bot.'.chr(10);
                        return $this->sendMessage($chatId, $text);
                    break;

                    case "/help":
                        $text = 'start - Bot info'.chr(10);
                        $text.= 'price - Shows price information (/price or /p coin )'.chr(10);
                        $text.= 'mprice - Shows multiple prices (/mprice or /mp coin1 coin2)'.chr(10);
                        $text.= 'gainers - Shows most profitable coins(or /g)'.chr(10);
                        $text.= 'losers - Shows the worst coins(or /l)'.chr(10);
                        $text.= 'chart - Draws chart for given interval (/chart or /c coin interval)'.chr(10);
                        $text.= 'charth - Draws chart with Heikin Ashi candles (/charth or /ch coin interval)'.chr(10);
                        $text.= 'chartb - Draws chart with Bollinger and BB% (/chartb or /cb coin interval)'.chr(10);
                        $text.= 'guppy - Draws chart with Guppy MA (/guppy or /cg coin interval)'.chr(10);
                        $text.= 'chartk - Draws chart with Keltner Channels (/chartk or /ck coin interval)'.chr(10);
                        $text.= 'chartp - Draws chart with Parabolic SAR (/chartp or /cp coin interval)'.chr(10);
                        $text.= 'chartf - Creates chart with Fibonacci Retracement Levels (/chartf or /cf coin interval)'.chr(10);

                        return $this->sendMessage($chatId, $text);
                    break;

                    case "/price":
                    case "/p":
                        if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        else{ $symbol = strtoupper($bot_command[1]); }

                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);
                        $coinfound = false;
                        foreach ($coin_list as $key => $coin){
                            if($symbol==$coin->symbol){
                                $url = 'https://api.binance.com/api/v3/ticker/price?symbol='.$symbol;
                                $data = $this->curlRequest($url);
                                $data = json_decode($data);
                                $text= $data->symbol.' : '.$data->price;
                                $text = $text.chr(10);
                                $coinfound = true;
                                break;
                            }
                        }
                        if(!$coinfound){
                            $text = "Sorry, such coins doesn't exist!".chr(10);
                        }
                        return $this->sendMessage($chatId, $text);
                        
                    break;

                    case "/mprice":
                    case "/mp":
                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);

                        $text = '';
                        $commandCount = count($bot_command);

                        if($commandCount<=1){ 
                            $url = 'https://api.binance.com/api/v3/ticker/price?symbol=BTCUSDT';
                            $data = $this->curlRequest($url);
                            $data = json_decode($data);
                            $text.= ($data->symbol.' : '.$data->price).chr(10);
                        }
                        else{
                            for ($i=0; $i<$commandCount; $i++){
                                if($i>0){
                                    if(!isset($bot_command[$i])){ $symbol = "BTCUSDT"; }
                                    else{ $symbol = strtoupper(trim($bot_command[$i])); }

                                    if($symbol==''){continue;}
                                    
                                    $coinfound = false;
                                    foreach ($coin_list as $key => $coin){
                                        if($symbol==$coin->symbol){
                                            $url = 'https://api.binance.com/api/v3/ticker/price?symbol='.$symbol;
                                            $data = $this->curlRequest($url);
                                            $data = json_decode($data);
                                            $text.= ($data->symbol.' : '.$data->price).chr(10);
                                            $coinfound = true;
                                            break;
                                        }
                                    }

                                    if(!$coinfound){
                                        $text.= $symbol." : Not a coin!".chr(10);
                                    }

                                    // $url = 'https://api.binance.com/api/v3/ticker/price?symbol='.strtoupper($bot_command[$i]);
                                    // $data = $this->curlRequest($url);
                                    // $data = json_decode($data);
                                    // $text.= ($data->symbol.' : '.$data->price).chr(10);
                                }
                            }
                        }
                        return $this->sendMessage($chatId, $text);
                    break;


                    case "/gainers":
                    case "/g":
                        $url = 'https://api.binance.com/api/v1/ticker/24hr';
                        $data = $this->curlRequest($url);
                        $data = json_decode($data,true);                
                        usort($data, function($a, $b) {
                            return $b['priceChangePercent'] - $a['priceChangePercent'];
                        });                  
                        $i = 1;        
                        $text = '<b>Binance Top 10 Gainers</b>'.chr(10);
                        foreach($data as $item){  
                            $p_smbl = (substr($item['symbol'], -3) == 'BTC')?' &#x20BF;':((substr($item['symbol'], -3) == 'ETH')?' ETH':((substr($item['symbol'], -3) == 'BNB')?' BNB':((substr($item['symbol'], -4) == 'USDT')? ' USDT': '')));
                            $text.='<b>'.$item['symbol'].'</b>'.chr(10);
                            $text.='Last Price : '.$item['lastPrice'].$p_smbl.chr(10);
                            $text.='24h Change : '.number_format($item['priceChangePercent'],1,'.','').'&#37;'.chr(10); 
                            $text.='Volume : '.number_format($item['volume'], 0, '.', ',').chr(10).chr(10);      
                            if($i == 10) {
                                break;
                            }
                            $i++;
                        }
                        return $this->sendMessage($chatId, $text);
                    break;


                    case "/losers":
                    case "/l":
                        $url = 'https://api.binance.com/api/v1/ticker/24hr';
                        $data = $this->curlRequest($url);
                        $data = json_decode($data,true);
                        usort($data, function($a, $b) {
                            return $a['priceChangePercent'] <=> $b['priceChangePercent'];
                        });
                        $i = 1;        
                        $text = '<b>Binance Top 10 Losers</b>'.chr(10);
                        foreach($data as $item){  
                            $p_smbl = (substr($item['symbol'], -3) == 'BTC')?' &#x20BF;':((substr($item['symbol'], -3) == 'ETH')?' ETH':((substr($item['symbol'], -3) == 'BNB')?' BNB':((substr($item['symbol'], -4) == 'USDT')? ' USDT': '')));
                            $text.='<b>'.$item['symbol'].'</b>'.chr(10);
                            $text.='Last Price : '.$item['lastPrice'].$p_smbl.chr(10);
                            $text.='24h Change : '.number_format($item['priceChangePercent'],1,'.','').'&#37;'.chr(10);
                            $text.='Volume : '.number_format($item['volume'], 0, '.', ',').chr(10).chr(10);                          
                            if($i == 10){
                                break;
                            }
                            $i++;
                        }
                        return $this->sendMessage($chatId, $text); 
                    break;


                    case "/chart":
                    case "/c":
                        $text = 'Processing...'.chr(10);
                        $this->sendMessage($chatId, $text);

                        $limit = 300;
                        if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        else{ $symbol = strtoupper($bot_command[1]); }
                        if(!isset($bot_command[2])){ $interval = "1h"; }
                        else{ $interval = $bot_command[2]; }

                        if($interval!='1M'){ $interval = strtolower($interval);  }

                        if(!in_array($interval,$interval_list)){
                            $text = "Sorry, the given interval doesn't exist!".chr(10);
                            $text.= "Available intervals : 1m, 3m, 5m, 15m, 30m, 1h, 2h, 4h, 6h, 12h, 1d, 1w, 1M";
                            return $this->sendMessage($chatId, $text);
                        }

                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);
                        $coinfound = false;
                        foreach ($coin_list as $key => $coin){
                            if($symbol==$coin->symbol){
                                $url = 'http://cryptoscannerpro.com/botsimpleChart/'.$symbol.'/'.$interval.'/'.$limit;
                                return $this->sendScreenshot($chatId,$url,"chart1.jpg");
                                $coinfound = true;
                                break;
                            }
                        }
                        if(!$coinfound){
                            $text = "Sorry, such coins doesn't exist!".chr(10);
                            $this->sendMessage($chatId, $text);
                        }
                    break;

                    case "/charth":
                    case "/ch":
                        // $text = 'Processing...'.chr(10);
                        // $this->sendMessage($chatId, $text);
                        // $limit = 300;
                        // if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        // else{ $symbol = strtoupper($bot_command[1]); }
                        // if(!isset($bot_command[2])){ $interval = "1h"; }
                        // else{ $interval = strtoupper($bot_command[2]); }
                        // $url = 'http://cryptoscannerpro.com/heikinashiChart/'.$symbol.'/'.$interval.'/'.$limit;
                        // return $this->sendScreenshot($chatId,$url,"chart2.jpg");

                        $text = 'Processing...'.chr(10);
                        $this->sendMessage($chatId, $text);

                        $limit = 300;
                        if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        else{ $symbol = strtoupper($bot_command[1]); }
                        if(!isset($bot_command[2])){ $interval = "1h"; }
                        else{ $interval = $bot_command[2]; }

                        if($interval!='1M'){ $interval = strtolower($interval);  }

                        if(!in_array($interval,$interval_list)){
                            $text = "Sorry, the given interval doesn't exist!".chr(10);
                            $text.= "Available intervals : 1m, 3m, 5m, 15m, 30m, 1h, 2h, 4h, 6h, 12h, 1d, 1w, 1M";
                            return $this->sendMessage($chatId, $text);
                        }

                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);
                        $coinfound = false;
                        foreach ($coin_list as $key => $coin){
                            if($symbol==$coin->symbol){
                                $url = 'http://cryptoscannerpro.com/heikinashiChart/'.$symbol.'/'.$interval.'/'.$limit;
                                return $this->sendScreenshot($chatId,$url,"chart1.jpg");
                                $coinfound = true;
                                break;
                            }
                        }
                        if(!$coinfound){
                            $text = "Sorry, such coins doesn't exist!".chr(10);
                            $this->sendMessage($chatId, $text);
                        }
                    break;


                    case "/guppy":
                    case "/cg":
                        // $text = 'Processing...'.chr(10);
                        // $this->sendMessage($chatId, $text);
                        // $limit = 300;
                        // if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        // else{ $symbol = strtoupper($bot_command[1]); }
                        // if(!isset($bot_command[2])){ $interval = "1h"; }
                        // else{ $interval = strtoupper($bot_command[2]); }
                        // $url = 'http://cryptoscannerpro.com/guppyChart/'.$symbol.'/'.$interval.'/'.$limit;
                        // return $this->sendScreenshot($chatId,$url,"chart3.jpg");

                        $text = 'Processing...'.chr(10);
                        $this->sendMessage($chatId, $text);

                        $limit = 300;
                        if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        else{ $symbol = strtoupper($bot_command[1]); }
                        if(!isset($bot_command[2])){ $interval = "1h"; }
                        else{ $interval = $bot_command[2]; }

                        if($interval!='1M'){ $interval = strtolower($interval);  }

                        if(!in_array($interval,$interval_list)){
                            $text = "Sorry, the given interval doesn't exist!".chr(10);
                            $text.= "Available intervals : 1m, 3m, 5m, 15m, 30m, 1h, 2h, 4h, 6h, 12h, 1d, 1w, 1M";
                            return $this->sendMessage($chatId, $text);
                        }

                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);
                        $coinfound = false;
                        foreach ($coin_list as $key => $coin){
                            if($symbol==$coin->symbol){
                                $url = 'http://cryptoscannerpro.com/guppyChart/'.$symbol.'/'.$interval.'/'.$limit;
                                return $this->sendScreenshot($chatId,$url,"chart1.jpg");
                                $coinfound = true;
                                break;
                            }
                        }
                        if(!$coinfound){
                            $text = "Sorry, such coins doesn't exist!".chr(10);
                            $this->sendMessage($chatId, $text);
                        }
                    break;

                    case "/chartb":
                    case "/cb":
                        // $text = 'Processing...'.chr(10);
                        // $this->sendMessage($chatId, $text);
                        // $limit = 300;
                        // if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        // else{ $symbol = strtoupper($bot_command[1]); }
                        // if(!isset($bot_command[2])){ $interval = "1h"; }
                        // else{ $interval = strtoupper($bot_command[2]); }
                        // $url = 'http://cryptoscannerpro.com/botBollingerbandChart/'.$symbol.'/'.$interval.'/'.$limit;
                        // return $this->sendScreenshot($chatId,$url,"chart4.jpg");

                        $text = 'Processing...'.chr(10);
                        $this->sendMessage($chatId, $text);

                        $limit = 300;
                        if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        else{ $symbol = strtoupper($bot_command[1]); }
                        if(!isset($bot_command[2])){ $interval = "1h"; }
                        else{ $interval = $bot_command[2]; }

                        if($interval!='1M'){ $interval = strtolower($interval);  }

                        if(!in_array($interval,$interval_list)){
                            $text = "Sorry, the given interval doesn't exist!".chr(10);
                            $text.= "Available intervals : 1m, 3m, 5m, 15m, 30m, 1h, 2h, 4h, 6h, 12h, 1d, 1w, 1M";
                            return $this->sendMessage($chatId, $text);
                        }

                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);
                        $coinfound = false;
                        foreach ($coin_list as $key => $coin){
                            if($symbol==$coin->symbol){
                                $url = 'http://cryptoscannerpro.com/botBollingerbandChart/'.$symbol.'/'.$interval.'/'.$limit;
                                return $this->sendScreenshot($chatId,$url,"chart1.jpg");
                                $coinfound = true;
                                break;
                            }
                        }
                        if(!$coinfound){
                            $text = "Sorry, such coins doesn't exist!".chr(10);
                            $this->sendMessage($chatId, $text);
                        }
                    break;

                    case "/chartk":
                    case "/ck":
                        // $text = 'Processing...'.chr(10);
                        // $this->sendMessage($chatId, $text);
                        // $limit = 300;
                        // if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        // else{ $symbol = strtoupper($bot_command[1]); }
                        // if(!isset($bot_command[2])){ $interval = "1h"; }
                        // else{ $interval = strtoupper($bot_command[2]); }
                        // $url = 'http://cryptoscannerpro.com/keltnerChart/'.$symbol.'/'.$interval.'/'.$limit;
                        // return $this->sendScreenshot($chatId,$url,"chart5.jpg");

                        $text = 'Processing...'.chr(10);
                        $this->sendMessage($chatId, $text);

                        $limit = 300;
                        if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        else{ $symbol = strtoupper($bot_command[1]); }
                        if(!isset($bot_command[2])){ $interval = "1h"; }
                        else{ $interval = $bot_command[2]; }

                        if($interval!='1M'){ $interval = strtolower($interval);  }

                        if(!in_array($interval,$interval_list)){
                            $text = "Sorry, the given interval doesn't exist!".chr(10);
                            $text.= "Available intervals : 1m, 3m, 5m, 15m, 30m, 1h, 2h, 4h, 6h, 12h, 1d, 1w, 1M";
                            return $this->sendMessage($chatId, $text);
                        }

                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);
                        $coinfound = false;
                        foreach ($coin_list as $key => $coin){
                            if($symbol==$coin->symbol){
                                $url = 'http://cryptoscannerpro.com/keltnerChart/'.$symbol.'/'.$interval.'/'.$limit;
                                return $this->sendScreenshot($chatId,$url,"chart1.jpg");
                                $coinfound = true;
                                break;
                            }
                        }
                        if(!$coinfound){
                            $text = "Sorry, such coins doesn't exist!".chr(10);
                            $this->sendMessage($chatId, $text);
                        }
                    break;

                    case "/chartp":
                    case "/cp":
                        // $text = 'Processing...'.chr(10);
                        // $this->sendMessage($chatId, $text);
                        // $limit = 300;
                        // if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        // else{ $symbol = strtoupper($bot_command[1]); }
                        // if(!isset($bot_command[2])){ $interval = "1h"; }
                        // else{ $interval = strtoupper($bot_command[2]); }
                        // $url = 'http://cryptoscannerpro.com/psarChart/'.$symbol.'/'.$interval.'/'.$limit;
                        // return $this->sendScreenshot($chatId,$url,"chart6.jpg");

                        $text = 'Processing...'.chr(10);
                        $this->sendMessage($chatId, $text);

                        $limit = 300;
                        if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        else{ $symbol = strtoupper($bot_command[1]); }
                        if(!isset($bot_command[2])){ $interval = "1h"; }
                        else{ $interval = $bot_command[2]; }

                        if($interval!='1M'){ $interval = strtolower($interval);  }

                        if(!in_array($interval,$interval_list)){
                            $text = "Sorry, the given interval doesn't exist!".chr(10);
                            $text.= "Available intervals : 1m, 3m, 5m, 15m, 30m, 1h, 2h, 4h, 6h, 12h, 1d, 1w, 1M";
                            return $this->sendMessage($chatId, $text);
                        }

                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);
                        $coinfound = false;
                        foreach ($coin_list as $key => $coin){
                            if($symbol==$coin->symbol){
                                $url = 'http://cryptoscannerpro.com/psarChart/'.$symbol.'/'.$interval.'/'.$limit;
                                return $this->sendScreenshot($chatId,$url,"chart1.jpg");
                                $coinfound = true;
                                break;
                            }
                        }
                        if(!$coinfound){
                            $text = "Sorry, such coins doesn't exist!".chr(10);
                            $this->sendMessage($chatId, $text);
                        }
                    break;

                    case "/chartf":
                    case "/cf":
                        // $text = 'Processing...'.chr(10);
                        // $this->sendMessage($chatId, $text);
                        // $limit = 300;
                        // if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        // else{ $symbol = strtoupper($bot_command[1]); }
                        // if(!isset($bot_command[2])){ $interval = "1h"; }
                        // else{ $interval = strtoupper($bot_command[2]); }
                        // $url = 'http://cryptoscannerpro.com/fibonacciChart/'.$symbol.'/'.$interval.'/'.$limit;
                        // return $this->sendScreenshot($chatId,$url,"chart6.jpg");


                        $text = 'Processing...'.chr(10);
                        $this->sendMessage($chatId, $text);

                        $limit = 300;
                        if(!isset($bot_command[1])){ $symbol = "BTCUSDT"; }
                        else{ $symbol = strtoupper($bot_command[1]); }
                        if(!isset($bot_command[2])){ $interval = "1h"; }
                        else{ $interval = $bot_command[2]; }

                        if($interval!='1M'){ $interval = strtolower($interval);  }

                        if(!in_array($interval,$interval_list)){
                            $text = "Sorry, the given interval doesn't exist!".chr(10);
                            $text.= "Available intervals : 1m, 3m, 5m, 15m, 30m, 1h, 2h, 4h, 6h, 12h, 1d, 1w, 1M";
                            return $this->sendMessage($chatId, $text);
                        }

                        $coins = $this->curlRequest("https://api.binance.com/api/v1/ticker/24hr");
                        $coin_list = json_decode($coins);
                        $coinfound = false;
                        foreach ($coin_list as $key => $coin){
                            if($symbol==$coin->symbol){
                                $url = 'http://cryptoscannerpro.com/fibonacciChart/'.$symbol.'/'.$interval.'/'.$limit;
                                return $this->sendScreenshot($chatId,$url,"chart1.jpg");
                                $coinfound = true;
                                break;
                            }
                        }
                        if(!$coinfound){
                            $text = "Sorry, such coins doesn't exist!".chr(10);
                            $this->sendMessage($chatId, $text);
                        }
                    break;


                    default:
                        return;
                }
            }
        }else if(!empty ($contents["callback_query"])){
            $chatId = $contents["callback_query"]["message"]["chat"]["id"];
            $user_id = $contents["callback_query"]["from"]["id"];
            $message_id = $contents["callback_query"]["message"]["message_id"];
            $message = $contents["callback_query"]["message"]["text"];

            $username = isset($contents["callback_query"]["from"]["username"]) ? $contents["callback_query"]["from"]["username"]:'';
            $last_name = isset($contents["callback_query"]["from"]["last_name"]) ? $contents["callback_query"]["from"]["last_name"] : '';
            $full_name = isset($contents["callback_query"]["from"]["first_name"]) ? $contents["callback_query"]["from"]["first_name"].' '.$last_name:'';
            switch($contents['callback_query']['data']){
                case "feedback_like":
                    echo "feedback_like";
                break;
                case "feedback_dislike":
                    echo "feedback_dislike";
                break;
                default:
                    return;
            }
        }else{
            return;
        }
        return;
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
    public static function sendMessage ($chat_id,$message, $reply = false, $mute = false, $inst_view = false,$keyboard = false){
    //   $botToken = env("TELEGRAM_API", "963741278:AAFVzAoTodNIApgKxu-qXbxcvbI6Z4YSLHo");
      $botToken = env("TELEGRAM_API", "1005524534:AAHVOm35HyDKY8Cf4GOGvuvEZfca7nTkwyE");
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

    public function  sendScreenshot($chat_id,$url,$image){
        $image='/home/cspxxvk1/public_html/public/telegram/bigchief/'.$image;
        $secret = "Konya";
        $hash = md5($url.$secret);
        $ch = curl_init('http://api.screenshotmachine.com/?key=cbae08&dimension=1024xfull&delay=8000&cacheLimit=0&url='.$url.'&hash='.$hash);//bigchief client

        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        // $chat_id    = '-1001410227642';//Big chief 
        // $chat_id    = '-1001342307796';//test big chief
        $bot_url    = "https://api.telegram.org/bot1005524534:AAHVOm35HyDKY8Cf4GOGvuvEZfca7nTkwyE/";
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




//Fibonacci Retracement 
    public function fibonacciChart($symbol,$interval,$limit){
        return view('bigchief/fibonacciChart')->with('symbol',$symbol)->with('interval',$interval)->with('limit',$limit);
    }
    public function fibonaccicandlesticks(Request $request){
        $symbol=$request->symbol;
        $interval=$request->interval;
        $limit=$request->limit;

        $url = 'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit;
        $response = $this->curlRequest($url);
        $response = json_decode($response,true);    
        
        $closeTime=[];
        $highArray=[];
        $lowArray=[];
        $volume = [];
        foreach ($response as $key => $value){
            $data[$key][0]=floatval($value[6]);//close time
            $data[$key][1]=floatval($value[1]);//open
            $data[$key][2]=floatval($value[2]);//high
            $data[$key][3]=floatval($value[3]);//low
            $data[$key][4]=floatval($value[4]);//close

            $volume[$key][0]=floatval($value[6]);
            $volume[$key][1]=floatval($value[7]);

            $closeTime[]=floatval($value[6]);//close time
            $highArray[]=floatval($value[2]);//high
            $lowArray[]=floatval($value[3]);//low
        }
        //0%, 23.6%, 38.2%, 50%, 61.8%, 100%
        $mincloseTime = min($closeTime);
        $maxcloseTime = max($closeTime);

        $maxHigh = max($highArray);
        $minLow = min($lowArray);

        $diff = $maxHigh-$minLow;
        $pcntg0     = $minLow;
        $pcntg236   = $minLow+($diff * 0.236);
        $pcntg382   = $minLow+($diff * 0.382);
        $pcntg50    = $minLow+($diff * 0.5);
        $pcntg618   = $minLow+($diff * 0.618);
        $pcntg100   = $maxHigh;

        $points['pcntg0']   = [[$mincloseTime,$pcntg0], [$maxcloseTime,$pcntg0]];
        $points['pcntg236'] = [[$mincloseTime,$pcntg236], [$maxcloseTime,$pcntg236]];
        $points['pcntg382'] = [[$mincloseTime,$pcntg382], [$maxcloseTime,$pcntg382]];
        $points['pcntg50']  = [[$mincloseTime,$pcntg50], [$maxcloseTime,$pcntg50]];
        $points['pcntg618'] = [[$mincloseTime,$pcntg618], [$maxcloseTime,$pcntg618]];
        $points['pcntg100'] = [[$mincloseTime,$pcntg100], [$maxcloseTime,$pcntg100]];

        return array($data, $points, $volume);
    }
//End of Fibonacci Retracement 

//Simple chart section
    public function botsimpleChart($symbol,$interval,$limit){
    	return view('bigchief/botChart')->with('symbol',$symbol)->with('interval',$interval)->with('limit',$limit);
    }
    public function botcandlesticks(Request $request){
        $symbol=$request->symbol;
    	$interval=$request->interval;
    	$limit=$request->limit;

        $url = 'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit;
        $response = $this->curlRequest($url);
        $response = json_decode($response,true);    
        
        $data = [];
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

        return array($data,$volume);
    }
//End of Simple chart section

//Heikin ashi chart
    public function heikinashiChart($symbol,$interval,$limit){
        return view('bigchief/heikinashiChart')->with('symbol',$symbol)->with('interval',$interval)->with('limit',$limit);
    }
    public function heikinashicandlesticks(Request $request){
        $symbol=$request->symbol;
    	$interval=$request->interval;
        $limit=$request->limit;
        
        // $limit=300;
        // $symbol = "ETHBTC";
        // $interval = '1m';

        $url = 'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit;
        $response = $this->curlRequest($url);
        $response = json_decode($response,true);    

        // Close= (Open+High+Low+Close)/4;(The average price of the current bar)
        // Open= (Open of Prev. Bar+Close of Prev. Bar)/2; (The midpoint of the previous bar)
        // High=Max[High, Open, Close]
        // Low=Min[Low, Open, Close]
        $prevOpen = 0;
        $prevClose = 0;

        $data = [];
        $volume = [];
        
        foreach ($response as $key => $value){
            $open       =floatval($value[1]);//open
            $high       =floatval($value[2]);//high
            $low        =floatval($value[3]);//low
            $close      =floatval($value[4]);//close

            $newClose   = ($open+$high+$low+$close)/4;
            if($key==0){ $newOpen    = ($open+$close)/2; }
            else{ $newOpen    = ($prevOpen+$prevClose)/2; }

            $newHigh    = max([$high,$newOpen,$newClose]);
            $newLow     = min([$low,$newOpen,$newClose]);

            $data[$key][0]=floatval($value[6]);//closetime
            $data[$key][1]=floatval($newOpen);//open
            $data[$key][2]=floatval($newHigh);//high
            $data[$key][3]=floatval($newLow);//low
            $data[$key][4]=floatval($newClose);//close

            $volume[$key][0]=floatval($value[6]);
            $volume[$key][1]=floatval($value[7]);

            $prevOpen = $open;
            $prevClose = $close;
        }

        return array($data, $volume);
    }
//End of Heikin ashi chart

//Guppy moving average
    public function guppyChart($symbol,$interval,$limit){
        return view('bigchief/guppyChart')->with('symbol',$symbol)->with('interval',$interval)->with('limit',$limit);
    }
    public function guppyCandlesticks(Request $request){
        $symbol=$request->symbol;
        $interval=$request->interval;
        $limit=$request->limit;

        $url = 'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit;
        $json_response = $this->curlRequest($url);
        $json_response = json_decode($json_response,true);    

        $data = [];
        $volume=[];
        foreach ($json_response as $key => $value){
            $closeTime  =floatval($value[6]);//closetime
            $open       =floatval($value[1]);//open
            $high       =floatval($value[2]);//high
            $low        =floatval($value[3]);//low
            $close      =floatval($value[4]);//close
            

            $data[$key][0]=floatval($closeTime);//closetime
            $data[$key][1]=floatval($open);//open
            $data[$key][2]=floatval($high);//high
            $data[$key][3]=floatval($low);//low
            $data[$key][4]=floatval($close);//close

            $volume[$key][0]=floatval($value[6]);
            $volume[$key][1]=floatval($value[7]);
            
        }

        $shortTermMa_list = [3, 5, 8, 10, 12, 15];
        $longTermMa_list = [30, 35, 40, 45, 50, 60];

        $ttArrayCnt = count($json_response);

        foreach ($shortTermMa_list as $shortTermMa_key => $shortTermMa_value) {
            // echo "$shortTermMa_key => $shortTermMa_value<br>";
            $guppyArray['short'][$shortTermMa_value] = [];
            for ($i=0; $i <= $ttArrayCnt; $i++) {

                if($i<=($ttArrayCnt-$shortTermMa_value)){
                    $ffArray = array_slice($json_response,$i,$shortTermMa_value,true);
                    $ffsum = 0;
                    foreach($ffArray as $ffkey=>$ffvalue){
                        $ffsum+=$ffvalue[4];
                        $ffclosetime = $ffvalue[6];
                    }
                    $ffAvarege = $ffsum/$shortTermMa_value;
                    // $guppyArray['short'][$shortTermMa_value][$i]['time']     = $ffclosetime;
                    // $guppyArray['short'][$shortTermMa_value][$i]['value']    = $ffAvarege;

                    $guppyArray['short'][$shortTermMa_value][$i] = [$ffclosetime, $ffAvarege];
                }

            }
        }

        foreach ($longTermMa_list as $longTermMa_key => $longTermMa_value) {
            // echo "$longTermMa_key => $longTermMa_value<br>";
            $guppyArray['long'][$longTermMa_value] = [];
            for ($i=0; $i <= $ttArrayCnt; $i++) {

                if($i<=($ttArrayCnt-$longTermMa_value)){
                    $ffArray = array_slice($json_response,$i,$longTermMa_value,true);
                    $ffsum = 0;
                    foreach($ffArray as $ffkey=>$ffvalue){
                        $ffsum+=$ffvalue[4];
                        $ffclosetime = $ffvalue[6];
                    }
                    $ffAvarege = $ffsum/$longTermMa_value;
                    $guppyArray['long'][$longTermMa_value][$i]     = [$ffclosetime, $ffAvarege];
                }

            }
        }

        return array($data, $guppyArray, $volume);
    }
//End of Guppy moving average

//Bollinger band 
    public function botBollingerbandChart($symbol,$interval,$limit){
        return view('bigchief/botBollingerChart')->with('symbol',$symbol)->with('interval',$interval)->with('limit',$limit);
    }
    public function botBollingerCandleSticks(Request $request){
        $symbol=$request->symbol;
        $interval=$request->interval;
        $limit=$request->limit;

        $url = 'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit;
        $json_response = $this->curlRequest($url);
        $json_response = json_decode($json_response,true);    

        foreach ($json_response as $key => $value){
            $closeTime  =floatval($value[6]);//closetime
            $open       =floatval($value[1]);//open
            $high       =floatval($value[2]);//high
            $low        =floatval($value[3]);//low
            $close      =floatval($value[4]);//close
            

            $data[$key][0]=floatval($closeTime);//closetime
            $data[$key][1]=floatval($open);//open
            $data[$key][2]=floatval($high);//high
            $data[$key][3]=floatval($low);//low
            $data[$key][4]=floatval($close);//close
            
        }

        $ttArrayCnt = count($json_response);
        $smaArray = [];
        $bandArray = [];
        $bandWidthArray = [];
        $number = 20;

        for ($i=0; $i <= $ttArrayCnt; $i++) {
            if($i<=($ttArrayCnt-$number)){
                $ffArray = array_slice($json_response,$i,$number,true);

                ///Calculating sma
                $ffsum = 0;
                foreach($ffArray as $ffkey=>$ffvalue){
                    $ffsum+=$ffvalue[4];//close value
                    $ffclosetime = $ffvalue[6];//close time
                }
                $smAvarege = $ffsum/$number;
                // $smaArray[$i]['time'] = $ffclosetime;
                // $smaArray[$i]['sma'] = $smAvarege;
                $smaArray[$i] = [$ffclosetime, $smAvarege];

                ///calculating Standard Deviation
                $sqSum=0;
                foreach($ffArray as $key1=>$value1){
                    $closeVal=$value1[4];//close value
                    $closetime = $value1[6];//close time
                    $sqSum+=pow(($smAvarege-$closeVal),2);
                }
                $stndDev = sqrt($sqSum/($number-1));//calculate standers deviation

                $upperBand = $smAvarege+($stndDev*2);//Upperband points
                $lowerBand = $smAvarege-($stndDev*2);//lowerband points

                // $smaArray[$i]['upper'] = $upperBand;
                // $smaArray[$i]['lower'] = $lowerBand;

                $bandArray[$i] = [$ffclosetime, $upperBand, $lowerBand];

                $lastbbWidth = (($upperBand-$lowerBand)/$smAvarege)*100 ;
                $bandWidthArray[$i] = [$ffclosetime, $lastbbWidth];

            }
            
        }

        return array($data, $smaArray, $bandArray, $bandWidthArray);


    }
//End of bollinger band

// Keltner Channels
    public function keltnerChart($symbol,$interval,$limit){
        return view('bigchief/keltnerChart')->with('symbol',$symbol)->with('interval',$interval)->with('limit',$limit);
    }
    public function keltnerCandleSticks(Request $request){
        $symbol=$request->symbol;
        $interval=$request->interval;
        $limit=$request->limit;

        $url = 'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit;
        $json_response = $this->curlRequest($url);
        $json_response = json_decode($json_response,true);    

        $data = [];
        $volume = [];
        foreach ($json_response as $key => $value){
            $closeTime  =floatval($value[6]);//closetime
            $open       =floatval($value[1]);//open
            $high       =floatval($value[2]);//high
            $low        =floatval($value[3]);//low
            $close      =floatval($value[4]);//close
            

            $data[$key][0]=floatval($closeTime);//closetime
            $data[$key][1]=floatval($open);//open
            $data[$key][2]=floatval($high);//high
            $data[$key][3]=floatval($low);//low
            $data[$key][4]=floatval($close);//close

            $volume[$key][0]=floatval($value[6]);
            $volume[$key][1]=floatval($value[7]);
            
        }

        $ttArrayCnt = count($json_response);
        // $smaArray = [];
        // $bandArray = [];
        // $bandWidthArray = [];
        $number = 20;
        $ema = 0;
        $emaArray = [];
        $bandArray = [];
        $prvClose = 0;

        for ($i=0; $i <= $ttArrayCnt; $i++) {
            if($i<=($ttArrayCnt-$number)){
                $ffArray = array_slice($json_response,$i,$number,true);

                ///Calculating sma
                $ffsum = 0;
                
                $trueRangesum=0;
                foreach($ffArray as $ffkey=>$ffvalue){
                    $ffsum+=$ffvalue[4];//close value
                    $ffclosetime = $ffvalue[6];//close time
                    $crntClosePrice = $ffvalue[4];//close value
                    $currentHigh = $ffvalue[2];//high value
                    $currentLow = $ffvalue[3];//low value

                    //geting true range
                    if($i==0){
                        $trueRange = ($currentHigh-$currentLow);
                    }else{
                        $trueRangeArray = [($currentHigh-$currentLow), ($currentHigh-$prvClose), ($prvClose-$currentLow)];
                        $trueRange = max($trueRangeArray);
                    }
                    $trueRangesum+=$trueRange;
                    $prvClose = $crntClosePrice;
                }
                $smAvarege = $ffsum/$number;
                $avgtrueRange = $trueRangesum/$number;//average true value

                
                $k= 2/($number+1);
                if($i==0){ 
                    $ema = ($crntClosePrice*$k)+($smAvarege*(1-$k));
                }else{ 
                    $ema = ($crntClosePrice*$k)+($ema*(1-$k)); 
                }
                $emaArray[] = [$ffclosetime, $ema];

                $upperBand = $ema+(2*$avgtrueRange);
                $lowerBand = $ema-(2*$avgtrueRange);

                $bandArray[] = [$ffclosetime, $upperBand, $lowerBand];
                

                
                
            }
            
        }

        return array($data, $emaArray, $bandArray, $volume);


    }
// End of Keltner Channels

//parabolic chart
    public function psarChart($symbol,$interval,$limit){
        return view('bigchief/psarChart')->with('symbol',$symbol)->with('interval',$interval)->with('limit',$limit);
    }
    public function psarcandlesticks(Request $request){
        $symbol=$request->symbol;
        $interval=$request->interval;
        $limit=$request->limit;

        $url = 'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit;
        $response = $this->curlRequest($url);
        $response = json_decode($response,true);    
        
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
        return array($data, $volume);
    }
//End of parabolic chart


public function reponsetest(){
    // $chat_id = '-348068575';
    // $text = "test by bot";
    // return Helper::sendMessage($chat_id, $text, false, 1, 1);

            // $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
    
            return $this->botsimpleChart('ETHBTC','1h',300);

}


}

