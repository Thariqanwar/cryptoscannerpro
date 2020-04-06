<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use \App\Model\Price_alert;
use \App\Model\Pivot;
use \App\Model\Wall;
use Helper;

class bigchief_priceAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Price Alert';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        //Price Alert
        $alert_ids = Price_alert::where('alert_status','=','1')->get();
        $response = Curl::to('https://api.binance.com/api/v1/ticker/24hr')->withContentType('application/json')->get();
        $full_datas     = json_decode($response); 
//        $cmc_response = Curl::to('https://api.coinmarketcap.com/v2/ticker/?convert=BTC')->withContentType('application/json')->get();
//        $cmc_data     = json_decode($cmc_response);
//        
//        //Code optimise need to do
//        if(!empty($full_datas)){
//            foreach($full_datas as $data){
//                if((substr($full_data->symbol, -3) == 'BTC') || (substr($full_data->symbol, -4) == 'USDT')){ 
//                    //
//                }
//            }
//        }
        
        
        // $text = '';
        // foreach($alert_ids as $alert){ 
        //     if(!empty($full_datas)){
        //       foreach($full_datas as $data){                 
        //         if( ($alert->alert_cat == 1 && (strtoupper($alert->alert_coin).'BTC' == $data->symbol)) || ($alert->alert_cat == 2 && (strtoupper($alert->alert_coin).'USDT' == $data->symbol))){                    
        //             if($alert->alert_condition == 1){
        //                 if($data->lastPrice >= $alert->alert_price)  {
        //                     $text= "<a href='tg://user?id=$alert->alert_user_id'>@$alert->alert_user_name</a> ".strtoupper($alert->alert_coin).' have triggered '.$alert->alert_price.' at Binance exchange'.chr(10).chr(10);
        //                     $p_smbl = (substr($data->symbol, -3) == 'BTC')?' &#x20BF;':((substr($data->symbol, -3) == 'ETH')?' ETH':((substr($data->symbol, -3) == 'BNB')?' BNB':((substr($data->symbol, -4) == 'USDT')? ' USDT': '')));
        //                     $text.='<b>'.$data->symbol.'</b>'.chr(10);
        //                     $text.='Last Price : '.rtrim($data->lastPrice,'0').$p_smbl.chr(10);
        //                     $text.='24h Change : '.number_format($data->priceChangePercent,2,'.','').'&#37;'.chr(10); 
        //                     $text.='Volume : '.number_format($data->volume, 0, '.', ',').chr(10).chr(10); 
        //                     $objp = Price_alert::find($alert->id); 
        //                     $objp->alert_status = 0;
        //                     $objp->save();
        //                     $text.='View all listed coins at <a href="https://icoincryptos.com/coins">website</a>'.chr(10).chr(10);
        //                     Helper::sendMessage($alert->alert_chat_id, $text, $alert->alert_message_id);
        //                 }
        //             }elseif($alert->alert_condition ==2){
        //                 if($data->lastPrice <= $alert->alert_price)  {
        //                     $text= "<a href='tg://user?id=$alert->alert_user_id'>@$alert->alert_user_name</a> ".strtoupper($alert->alert_coin).' have triggered '.$alert->alert_price.' at Binance exchange'.chr(10).chr(10);
        //                     $p_smbl = (substr($data->symbol, -3) == 'BTC')?' &#x20BF;':((substr($data->symbol, -3) == 'ETH')?' ETH':((substr($data->symbol, -3) == 'BNB')?' BNB':((substr($data->symbol, -4) == 'USDT')? ' USDT': '')));
        //                     $text.='<b>'.$data->symbol.'</b>'.chr(10);
        //                     $text.='Last Price : '.rtrim($data->lastPrice,'0').$p_smbl.chr(10);
        //                     $text.='24h Change : '.number_format($data->priceChangePercent,2,'.',',').'&#37;'.chr(10); 
        //                     $text.='Volume : '.number_format($data->volume, 0, '.', ',').chr(10).chr(10);
        //                     $objp = Price_alert::find($alert->id); 
        //                     $objp->alert_status = 0;
        //                     $objp->save();
        //                     $text.='View all listed coins at <a href="https://icoincryptos.com/coins">website</a>'.chr(10).chr(10);
        //                     Helper::sendMessage($alert->alert_chat_id, $text, $alert->alert_message_id);
        //                 }
        //             } 
        //             break;
        //         }                
        //       }
        //     }
            
        //     /*
        //     foreach($cmc_data->data as $cmcdata){
        //         if($cmcdata->symbol == strtoupper($alert->alert_coin)){
        //             if($alert->alert_cat == 1 && $alert->alert_condition == 1 ){
        //                 if($cmcdata->quotes->BTC->price > $alert->alert_price)  {
        //                     $text= strtoupper($alert->alert_coin).' have triggered '.$alert->alert_price.' at CoinMarketCap'.chr(10);                            
        //                     $text.='<b>'.$cmcdata->name.' ('.$cmcdata->symbol.')</b> '.chr(10); 
        //                     $text.='Last Price : '.$cmcdata->quotes->BTC->price.' '.$cmcdata->symbol.chr(10);
        //                     $text.='24h Change : '.$cmcdata->quotes->BTC->percent_change_24h.'&#37;'.chr(10); 
        //                     $text.='Volume : '.number_format($cmcdata->quotes->BTC->volume_24h, 2, '.', '').chr(10).chr(10); 
        //                     $objp = Price_alert::find($alert->id); 
        //                     $objp->alert_status = 0;
        //                     $objp->save();
        //                     $text.='View all listed coins at <a href="https://icoincryptos.com/coins">website</a>'.chr(10).chr(10);
        //                     Helper::sendMessage($alert->alert_chat_id, $text);
        //                 }
        //             }else if($alert->alert_cat == 1 && $alert->alert_condition == 2){
        //                 if($cmcdata->quotes->BTC->price < $alert->alert_price)  {
        //                     $text= strtoupper($alert->alert_coin).' have triggered '.$alert->alert_price.' at CoinMarketCap'.chr(10);                            
        //                     $text.='<b>'.$cmcdata->name.' ('.$cmcdata->symbol.')</b> '.chr(10); 
        //                     $text.='Last Price : '.$cmcdata->quotes->BTC->price.' '.$cmcdata->symbol.chr(10);
        //                     $text.='24h Change : '.$cmcdata->quotes->BTC->percent_change_24h.'&#37;'.chr(10); 
        //                     $text.='Volume : '.number_format($cmcdata->quotes->BTC->volume_24h, 2, '.', '').chr(10).chr(10); 
        //                     $objp = Price_alert::find($alert->id); 
        //                     $objp->alert_status = 0;
        //                     $objp->save();
        //                     $text.='View all listed coins at <a href="https://icoincryptos.com/coins">website</a>'.chr(10).chr(10);
        //                     Helper::sendMessage($alert->alert_chat_id, $text);
        //                 }
        //             }else if($alert->alert_cat == 2 && $alert->alert_condition == 1 ){
        //                 if($cmcdata->quotes->USD->price > $alert->alert_price)  {
        //                     $text= strtoupper($alert->alert_coin).' have triggered '.$alert->alert_price.' at CoinMarketCap'.chr(10);                            
        //                     $text.='<b>'.$cmcdata->name.' ('.$cmcdata->symbol.')</b> '.chr(10); 
        //                     $text.='Last Price : '.$cmcdata->quotes->USD->price.' '.$cmcdata->symbol.chr(10);
        //                     $text.='24h Change : '.$cmcdata->quotes->USD->percent_change_24h.'&#37;'.chr(10); 
        //                     $text.='Volume : '.number_format($cmcdata->quotes->USD->volume_24h, 2, '.', '').chr(10).chr(10); 
        //                     $objp = Price_alert::find($alert->id); 
        //                     $objp->alert_status = 0;
        //                     $objp->save();
        //                     $text.='View all listed coins at <a href="https://icoincryptos.com/coins">website</a>'.chr(10).chr(10);
        //                     Helper::sendMessage($alert->alert_chat_id, $text);
        //                 }
        //             }else if($alert->alert_cat == 2 && $alert->alert_condition == 2){
        //                 if($cmcdata->quotes->USD->price < $alert->alert_price)  {
        //                     $text= strtoupper($alert->alert_coin).' have triggered '.$alert->alert_price.' at CoinMarketCap'.chr(10);                            
        //                     $text.='<b>'.$cmcdata->name.' ('.$cmcdata->symbol.')</b> '.chr(10); 
        //                     $text.='Last Price : '.$cmcdata->quotes->USD->price.' '.$cmcdata->symbol.chr(10);
        //                     $text.='24h Change : '.$cmcdata->quotes->USD->percent_change_24h.'&#37;'.chr(10); 
        //                     $text.='Volume : '.number_format($cmcdata->quotes->USD->volume_24h, 2, '.', '').chr(10).chr(10); 
        //                     $objp = Price_alert::find($alert->id); 
        //                     $objp->alert_status = 0;
        //                     $objp->save();
        //                     $text.='View all listed coins at <a href="https://icoincryptos.com/coins">website</a>'.chr(10).chr(10);
        //                     Helper::sendMessage($alert->alert_chat_id, $text);
        //                 }
        //             }
        //             break;
        //         }
        //     }
        //     */
        // }
        
        //Channel Alert
        $text = $trade_status = '';         
//      channel id (icoincryptosbot) -1001325524184
        $excludes = array('VETBTC','VENBTC','VENUSDT','BCNBTC','RPXBTC','CHATBTC','TRIGBTC','ICNBTC','HSRBTC','PAXBTC','PAXUSDT','USDCUSDT','USDCBTC','DENTBTC','DENTUSDT','MODBTC','SALTBTC','SUBBTC','WINGSBTC','CLOAKBTC','BCHSVBTC','BCHSVUSDT');
        if($full_datas){
            foreach($full_datas as $full_data){  
                $percentage = ($full_data->quoteVolume*5)/100;                
                if(((substr($full_data->symbol, -3) == 'BTC') || (substr($full_data->symbol, -4) == 'USDT')) && (!in_array($full_data->symbol,$excludes))){ 
                    $response = Curl::to('https://api.binance.com/api/v1/depth?symbol='.$full_data->symbol.'&limit=100')->withContentType('application/json')->get();
                    $datas     = json_decode($response,TRUE);   
                    if(!empty($datas['bids']) && !empty($datas['asks'])){
                        usort($datas['bids'], function($a, $b) {
                            return $a[1] <= $b[1];
                        });

                        usort($datas['asks'], function($a, $b) {
                            return $a[1] <= $b[1];
                        });
                        
                        foreach($datas['bids'] as $key => $data){
                            if($data[0] < '0.000005')
                                break;
                            
                            $bid_amount = number_format(($data[1]*$data[0]), 4,'.', ',');
                            $ask_amount = number_format(($datas['asks'][$key][1]*$datas['asks'][$key][0]), 4,'.', ',');
                            
                            $buy_wall = Wall::where('wall_symbol','=',$full_data->symbol)->where('wall_type','=',1)->first();                            
                            $sell_wall = Wall::where('wall_symbol','=',$full_data->symbol)->where('wall_type','=',2)->first();                                                               
                            if(!$buy_wall || (($buy_wall->timestamp + 28800) <= strtotime(date('Y-m-d G:i:s')))){                               
                                if($percentage < $bid_amount && ($bid_amount > $ask_amount || $sell_wall) && $bid_amount > 25){                                                                                
                                        $text = '<b>'.$full_data->symbol.'</b>(Binance)'.chr(10).Helper::getEmoji('\xE2\x9D\x87').' <b>Buy Wall</b> '.number_format($bid_amount, 2,'.', ''). ((substr($full_data->symbol, -3) == 'BTC')?' BTC':' USDT').chr(10).'at '.$data[0].((substr($full_data->symbol, -3) == 'BTC')?'&#x20BF;':'&#36;').chr(10).chr(10).'Last Price : '.$full_data->lastPrice.((substr($full_data->symbol, -3) == 'BTC')?'&#x20BF;':'&#36;');
                                        Helper::sendMessage('-348068575', $text,false,1,false); //-1001280231567
                                        if($buy_wall){
                                            $wall_obj = Wall::find($buy_wall->id);
                                        }else{
                                            $wall_obj = new Wall();
                                        }                                        
                                        $wall_obj->wall_symbol = $full_data->symbol;
                                        $wall_obj->wall_type = 1;     
                                        $wall_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                                         
                                        $wall_obj->save();                                                                             
                                } 
                            }
                            if(!$sell_wall || (($sell_wall->timestamp + 28800) <= strtotime(date('Y-m-d G:i:s')))){ 
                                if($percentage < $ask_amount && ($bid_amount < $ask_amount || $buy_wall) && $ask_amount > 25){                                       
                                        $text = '<b>'.$full_data->symbol.'</b>(Binance)'.chr(10).'&#128315; <b>Sell Wall</b> '.number_format($ask_amount, 2,'.', '').((substr($full_data->symbol, -3) == 'BTC')?' BTC':' USDT').chr(10).'at '.$datas['asks'][$key][0].((substr($full_data->symbol, -3) == 'BTC')?'&#x20BF;':'&#36;').chr(10).chr(10).'Last Price : '.$full_data->lastPrice.((substr($full_data->symbol, -3) == 'BTC')?'&#x20BF;':'&#36;');
                                        Helper::sendMessage('-348068575', $text,false,1,false);//-1001280231567
                                        if($sell_wall){
                                            $wall_obj = Wall::find($sell_wall->id);
                                        }else{
                                            $wall_obj = new Wall();
                                        } 
                                        $wall_obj->wall_symbol = $full_data->symbol;
                                        $wall_obj->wall_type = 2;   
                                        $wall_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                                         
                                        $wall_obj->save();                                                                                  
                                }     
                            }                                                        
                            break;
                        }
                        
                    }
                    
                    if($full_data->lastPrice >= '0.000004'){
                        //Overbought && Oversold
                        $hr_response = Curl::to('https://api.binance.com/api/v1/klines?symbol='.$full_data->symbol.'&interval=1h&limit=150')->withContentType('application/json')->get();
                        $hr_datas = json_decode($hr_response,TRUE);                                            

                        $overbought = Wall::where('wall_symbol','=',$full_data->symbol)->where('wall_type','=',3)->first();                            
                        $oversold = Wall::where('wall_symbol','=',$full_data->symbol)->where('wall_type','=',4)->first();

                        if((!$overbought || (($overbought->timestamp + 14400) <= strtotime(date('Y-m-d G:i:s'))))){
                            $rsi = Helper::calculateRSI($hr_datas);  
                            if(end($rsi) >= 75){ 
                                $text = '<b>'.$full_data->symbol.'</b> (Binance)'.chr(10);
                                $text.= Helper::getEmoji('\xE2\x9D\x87').' <b>Overbought</b> on <b>1hr</b> timeframe'.chr(10);
                                $text.= Helper::getEmoji('\xF0\x9F\x91\x89').' P: '.$full_data->lastPrice .' | RSI: '.number_format(end($rsi), 2, '.', '').chr(10).'Vol 24h: '.number_format($full_data->quoteVolume,2,'.','').((substr($full_data->symbol, -3) == 'BTC')?' BTC':' USDT').chr(10);
                                $day_response = Curl::to('https://api.binance.com/api/v1/klines?symbol='.$full_data->symbol.'&interval=1d&limit=150')->withContentType('application/json')->get();
                                $day_datas = json_decode($day_response,TRUE);                     
                                $day_rsi = Helper::calculateRSI($day_datas);
                                $four_response = Curl::to('https://api.binance.com/api/v1/klines?symbol='.$full_data->symbol.'&interval=4h&limit=150')->withContentType('application/json')->get();
                                $four_datas = json_decode($four_response,TRUE);                     
                                $four_rsi = Helper::calculateRSI($four_datas);
                                $text.= 'RSI (1d : '.number_format(end($day_rsi), 2, '.', '').' | 4h : '.number_format(end($four_rsi), 2, '.', '').')'.chr(10);                              
                                $pivot = Pivot::where('symbol','=',$full_data->symbol)->first();
                                $up_chart = Helper::getEmoji('\xF0\x9F\x93\x88');
                                $down_chart = Helper::getEmoji('\xF0\x9F\x93\x89');
                                $text.=chr(10).'<b>Support/Resistance levels</b>'.chr(10);
                                $text.=$up_chart.' R3 :'.$pivot->resistance3.chr(10).$up_chart.' R2 :'.$pivot->resistance2.chr(10).$up_chart.' R1 :'.$pivot->resistance1.chr(10) . Helper::getEmoji('\xF0\x9F\x94\xB3').' PP :'.$pivot->pivot_point.chr(10).$down_chart.' S1 :'.$pivot->support1.chr(10).$down_chart.' S2 :'.$pivot->support2.chr(10).$down_chart.' S3 :'.$pivot->support3; 
                                Helper::sendMessage('-348068575', $text, false, 1, false );//-1001280231567  //-1001325524184
                                if($overbought){
                                    $bought_obj = Wall::find($overbought->id);
                                }else{
                                    $bought_obj = new Wall();
                                }                                        
                                $bought_obj->wall_symbol = $full_data->symbol;
                                $bought_obj->wall_type = 3;                            
                                $bought_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                                $bought_obj->save(); 
                            }
                        } if((!$oversold || (($oversold->timestamp + 14400) <= strtotime(date('Y-m-d G:i:s'))))){
                            $rsi = Helper::calculateRSI($hr_datas);
                            if(end($rsi) <= 25 &&  !empty($rsi)){
                                $text = '<b>'.$full_data->symbol.'</b> (Binance)'.chr(10);
                                $text.= '&#128315; <b>Oversold</b> on <b>1hr</b> timeframe'.chr(10);
                                $text.= Helper::getEmoji('\xF0\x9F\x91\x89').' P: '.$full_data->lastPrice .' | RSI: '.number_format(end($rsi), 2, '.', '').chr(10).'Vol 24h: '.number_format($full_data->quoteVolume,2,'.','').((substr($full_data->symbol, -3) == 'BTC')?' BTC':' USDT').chr(10);
                                $day_response = Curl::to('https://api.binance.com/api/v1/klines?symbol='.$full_data->symbol.'&interval=1d&limit=150')->withContentType('application/json')->get();
                                $day_datas = json_decode($day_response,TRUE);                     
                                $day_rsi = Helper::calculateRSI($day_datas);
                                $four_response = Curl::to('https://api.binance.com/api/v1/klines?symbol='.$full_data->symbol.'&interval=4h&limit=150')->withContentType('application/json')->get();
                                $four_datas = json_decode($four_response,TRUE);                     
                                $four_rsi = Helper::calculateRSI($four_datas);
                                $text.= 'RSI (1d : '.number_format(end($day_rsi), 2, '.', '').' | 4h : '.number_format(end($four_rsi), 2, '.', '').')'.chr(10);                                              
                                $pivot = Pivot::where('symbol','=',$full_data->symbol)->first();                            
                                $up_chart = Helper::getEmoji('\xF0\x9F\x93\x88');
                                $down_chart = Helper::getEmoji('\xF0\x9F\x93\x89');
                                $text.=chr(10).'<b>Support/Resistance levels</b>'.chr(10);                                
                                $text.=$up_chart.' R3 :'.$pivot->resistance3.chr(10).$up_chart.' R2 :'.$pivot->resistance2.chr(10).$up_chart.' R1 :'.$pivot->resistance1.chr(10) . Helper::getEmoji('\xF0\x9F\x94\xB3').' PP :'.$pivot->pivot_point.chr(10).$down_chart.' S1 :'.$pivot->support1.chr(10).$down_chart.' S2 :'.$pivot->support2.chr(10).$down_chart.' S3 :'.$pivot->support3;
                                Helper::sendMessage('-348068575', $text, false, 1, false );//677069447 //
                                if($oversold){
                                    $sold_obj = Wall::find($oversold->id);
                                }else{
                                    $sold_obj = new Wall();
                                }                                        
                                $sold_obj->wall_symbol = $full_data->symbol;
                                $sold_obj->wall_type = 4;                            
                                $sold_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                                $sold_obj->save(); 
                            }
                        }  
                        
                       $macd_data = Wall::where('wall_symbol','=',$full_data->symbol)->where('wall_type','=',5)->first();                     
                        if(!$macd_data || (($macd_data->timestamp + 14400) <= strtotime(date('Y-m-d G:i:s')))){
                            $macd = Helper::calculateMACD($hr_datas);
                            if(!empty($macd) && end($macd['histogram']) > 0 && $macd['histogram'][count($macd['histogram'])-2] < 0 ){                        
                                if(end($macd['difference']) > end($macd['signal']) && $macd['difference'][count($macd['difference'])-2] <= $macd['signal'][count($macd['signal'])-2]){
                                    $trade_status = Helper::getEmoji('\xE2\x9D\x87').' Bullish ';
                                }

                                $text= '<b><a href="https://www.binance.com/indexSpa.html#/trade/index?symbol='.$full_data->symbol.'">#'.$full_data->symbol.'</a></b> (Binance)'.chr(10);
                                $text.= '<b>'.$trade_status.'</b>MACD <b>Crossover</b> on the <b>1hr</b> timeframe'.chr(10);
                                $text.= 'P: '.$full_data->lastPrice .' | MACD: '.number_format(end($macd['difference']), 8, '.', '').chr(10).'Vol 24h: '.number_format($full_data->quoteVolume,2,'.','').((substr($full_data->symbol, -3) == 'BTC')?' BTC':' USDT').chr(10);
                                $text.= 'RSI : '.number_format(end($rsi), 4, '.', '').chr(10); 
                                Helper::sendMessage('-348068575', $text, false, 1, false); //-1001325524184
                                if($macd_data){
                                    $macd_obj = Wall::find($macd_data->id);
                                }else{
                                    $macd_obj = new Wall();
                                }                                        
                                $macd_obj->wall_symbol = $full_data->symbol;
                                $macd_obj->wall_type = 5;                            
                                $macd_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                                $macd_obj->save(); 
                            }
                        } 
                        
                        $four_hr_response = Curl::to('https://api.binance.com/api/v1/klines?symbol='.$full_data->symbol.'&interval=4h&limit=150')->withContentType('application/json')->get();
                        $four_hr_datas = json_decode($four_hr_response,TRUE); 
                        $macd_data = Wall::where('wall_symbol','=',$full_data->symbol)->where('wall_type','=',5)->first();                     
                        if(!$macd_data || (($macd_data->timestamp + 14400) <= strtotime(date('Y-m-d G:i:s')))){
                            $macd = Helper::calculateMACD($four_hr_datas);
                            if(!empty($macd) && end($macd['histogram']) > 0 && $macd['histogram'][count($macd['histogram'])-2] < 0 ){                        
                                if(end($macd['difference']) > end($macd['signal']) && $macd['difference'][count($macd['difference'])-2] <= $macd['signal'][count($macd['signal'])-2]){
                                    $trade_status = Helper::getEmoji('\xE2\x9D\x87').' Bullish ';
                                }

                                $text= '<b>#'.$full_data->symbol.'</b> (Binance)'.chr(10);
                                $text.= '<b>'.$trade_status.'</b>MACD <b>Crossover</b> on the <b>4hr</b> timeframe'.chr(10);
                                $text.= 'P: '.$full_data->lastPrice .' | MACD: '.number_format(end($macd['difference']), 8, '.', '').chr(10).'Vol 24h: '.number_format($full_data->quoteVolume,2,'.','').((substr($full_data->symbol, -3) == 'BTC')?' BTC':' USDT').chr(10);
                                $text.= 'RSI : '.number_format(end($rsi), 4, '.', '').chr(10); 
                                Helper::sendMessage('-348068575', $text, false, 1, false); //-1001325524184
                                if($macd_data){
                                    $macd_obj = Wall::find($macd_data->id);
                                }else{
                                    $macd_obj = new Wall();
                                }                                        
                                $macd_obj->wall_symbol = $full_data->symbol;
                                $macd_obj->wall_type = 5;                            
                                $macd_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                                $macd_obj->save(); 
                            }
                        }  

                //                    $rsi = isset($rsi)? $rsi : Helper::calculateRSI($hr_datas);
                //                    $pivot_txt = Helper::getPivotTxt($full_data->symbol, $full_data->lastPrice);                   
                //                    Helper::sendMessage('-1001280231567', $pivot_txt, false, 1, false );

                        //Unusual Activity 1hr Time Frame
                        if($hr_datas){
                            $vol_perc = ($full_data->volume*15)/100;
                            $vol = $full_data->lastPrice * end($hr_datas)[5];
                            if($vol_perc < end($hr_datas)[5] && $vol >= 5){
                                $activity = Wall::where('wall_symbol','=',$full_data->symbol)->where('wall_type','=',11)->first();
                                if(!$activity || $activity->timestamp + 3600 <= strtotime(date('Y-m-d G:i:s'))){
                                    $c_perc = (end($hr_datas)[5] / $full_data->volume)*100;
                                    $act_status = ($full_data->priceChangePercent > 0 ) ? ' <b>buying</b>':' <b>selling</b>';
                                    $act_symbol = ($full_data->priceChangePercent > 0 ) ? Helper::getEmoji('\xE2\x9D\x87') : '&#128315;';
                                    $text ='<b>#'.$full_data->symbol.'</b> (Binance)'.chr(10);
                                    $text.= $act_symbol.' Unusual'.$act_status.' activity '.chr(10). number_format($vol,($vol>1)?2:4,'.','').((substr($full_data->symbol, -3) == 'BTC')?' BTC':' USDT').' in <b>1hr</b> timeframe ('.number_format($c_perc,2,'.','').' &#37;)'.chr(10);
                                    $text.= '24hr Vol : '. number_format($full_data->quoteVolume,($vol>1)?2:4,'.','').((substr($full_data->symbol, -3) == 'BTC')?' &#x20BF;':' $').chr(10).'LTP : '.$full_data->lastPrice. ' ('.number_format($full_data->priceChangePercent,2,'.','').' &#37;)';
                                    Helper::sendMessage('-348068575', $text, false, 1, false );
                                    if($activity){
                                        $act_obj = Wall::find($activity->id);
                                    }else{
                                        $act_obj = new Wall();
                                    }                                        
                                    $act_obj->wall_symbol = $full_data->symbol;
                                    $act_obj->wall_type = 11;                            
                                    $act_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                                    $act_obj->save(); 
                                }
                            }     
                        }

                        $min_response = Curl::to('https://api.binance.com/api/v1/klines?symbol='.$full_data->symbol.'&interval=4h&limit=150')->withContentType('application/json')->get();
                        $min_datas = json_decode($min_response,TRUE);   

                        //Unusual Activity 4hr Time Frame
                        if($min_datas){
                            
                            $vol_perc = ($full_data->volume*10)/100;  
                            $vol = $full_data->lastPrice * end($min_datas)[5];
                            if($vol_perc < end($min_datas)[5] && $vol >= 5){
                                $activity = Wall::where('wall_symbol','=',$full_data->symbol)->where('wall_type','=',12)->first();
                                if(!$activity || $activity->timestamp + 3600 <= strtotime(date('Y-m-d G:i:s'))){  
                                    $c_perc = (end($min_datas)[5] / $full_data->volume)*100;
                                    $act_status = ($full_data->priceChangePercent > 0 ) ? ' <b>buying</b>':' <b>selling</b>';
                                    $act_symbol = ($full_data->priceChangePercent > 0 ) ? Helper::getEmoji('\xE2\x9D\x87') : '&#128315;';
                                    $text ='<b>'.$full_data->symbol.'</b> (Binance)'.chr(10);
                                    $text.= $act_symbol.' Unusual'.$act_status.' activity '.chr(10). number_format($vol,($vol>1)?2:4,'.','').((substr($full_data->symbol, -3) == 'BTC')?' BTC':' USDT').' in <b>4hr</b> timeframe ('.number_format($c_perc,2,'.','').' &#37;)'.chr(10);
                                    $text.= '24hr Vol : '. number_format($full_data->quoteVolume,($vol>1)?2:4,'.','').((substr($full_data->symbol, -3) == 'BTC')?' &#x20BF;':' $').chr(10).'LTP : '.$full_data->lastPrice. ' ('.number_format($full_data->priceChangePercent,2,'.','').' &#37;)';
                                  //  Helper::sendMessage('-1001280231567', $text, false, 1, false ); -1001280231567
                                    if($activity){ 
                                        $act_obj = Wall::find($activity->id);
                                    }else{
                                        $act_obj = new Wall();
                                    }                                        
                                    $act_obj->wall_symbol = $full_data->symbol;
                                    $act_obj->wall_type = 12;                            
                                    $act_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                                    $act_obj->save(); 
                                }
                            }     
                        }
                    }
                }               
            } 
        }
    }
}
