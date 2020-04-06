<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use \App\Model\Telegram_chat;
use Helper;

class bigchief_topGainers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'top:gainers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Top 10 Gainers';

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
        // $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
        
        //Top 10 Gainers Vs Losers Alert
        $response = Curl::to('https://api.binance.com/api/v1/ticker/24hr')->withContentType('application/json')->get();
        $data     = json_decode($response , TRUE); 
        usort($data, function($a, $b) {
            return $b['priceChangePercent'] - $a['priceChangePercent'];
        });                  
        $i = 1;        
        $text = '<b>Binance Top 10 Gainers</b>'.chr(10);
        foreach($data as $item){             
            if(substr($item['symbol'], -3) == 'BTC'){                
                $text.=$i.'. '.substr($item['symbol'], 0, -3).':'.rtrim(number_format($item['lastPrice'], 10,'.', ','),'0').'&#x20BF;('.number_format($item['priceChangePercent'],1,'.','').'&#37;)'.chr(10);     
                if($i == 10)                    
                    break;                
                $i++;
            }
        }
        usort($data, function($a, $b) {
            return $a['priceChangePercent'] <=> $b['priceChangePercent'];
        });                  
        $i = 1;        
        $text.= chr(10).'<b>Binance Top 10 Losers</b>'.chr(10);
        foreach($data as $item){  
            if(substr($item['symbol'], -3) == 'BTC'){                
                $text.=$i.'. '.substr($item['symbol'], 0, -3).':'.rtrim(number_format($item['lastPrice'], 10,'.', ','),'0').'&#x20BF;('.number_format($item['priceChangePercent'],1,'.','').'&#37;)'.chr(10);                          
                if($i == 10){
                    // $text.='View all at <a href="https://icoincryptos.com/gainers-losers">website</a>'.chr(10);
                    // $text.='Join us @icoincryptos'.chr(10).chr(10);
                    break;
                }
                $i++;
            }
        }
        
        // foreach ( $chat_ids as $chat_id) {        
            Helper::sendMessage('-348068575', $text, false, 1, 1);                  
        // } 



        
//        $text = '<b>Binance Top 10 Gainers</b>'.chr(10).chr(10);
//        foreach($data as $item){ 
//            $p_smbl = (substr($item['symbol'], -3) == 'BTC')?' &#x20BF;':((substr($item['symbol'], -3) == 'ETH')?' ETH':((substr($item['symbol'], -3) == 'BNB')?' BNB':((substr($item['symbol'], -4) == 'USDT')? ' USDT': '')));
//            $text.='<b>'.$item['symbol'].'</b>'.chr(10);
//            $text.='Last Price : '.$item['lastPrice'].$p_smbl.chr(10);
//            $text.='24h Change : '.$item['priceChangePercent'].'&#37;'.chr(10); 
//            $text.='Volume : '.number_format($item['volume'], 0, '.', '').chr(10).chr(10);      
//            if($i == 10) {
//                $text.='View all at <a href="https://icoincryptos.com/gainers-losers">website</a>'.chr(10);
//                $text.='Join us @icoincryptos'.chr(10).chr(10);
//                break;
//            }
//            $i++;
//        }                
    }
}
