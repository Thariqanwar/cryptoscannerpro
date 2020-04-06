<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use \App\Model\Telegram_chat;
use Helper;

class bigchief_mcapAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mcap:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Global Marcket Cap';

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
        
        //Global Market Cap
        $response = Curl::to('https://api.coinmarketcap.com/v2/global/?convert=BTC')->withContentType('application/json')->get();
        $data     = json_decode($response);                 
        $text = '<b>Global Market Cap</b>'.chr(10);
        $text.= 'MCap: $'.number_format($data->data->quotes->USD->total_market_cap, 0,'.', ',').chr(10);                        
        $text.= '24h Vol: $'.number_format($data->data->quotes->USD->total_volume_24h, 0,'.', ',').chr(10);
        $text.= 'BTC Cap: &#x20BF;'.number_format($data->data->quotes->BTC->total_market_cap, 0,'.', ',').chr(10);
        $text.= 'BTC Dominance: '.$data->data->bitcoin_percentage_of_market_cap.'&#37;';
//        Helper::sendMessage('-1001280231567', $text); 
        Helper::sendMessage('-348068575', $text, false, 1, 1);// our schannel
        // foreach ( $chat_ids as $chat_id) {        
        //     Helper::sendMessage($chat_id->chat_id, $text,false,1,false);                  
        // } 
    }
}
