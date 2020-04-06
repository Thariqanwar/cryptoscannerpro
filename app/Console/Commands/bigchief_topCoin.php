<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use \App\Model\Telegram_chat;
use Helper;

class bigchief_topCoin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'top:coin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Top 10 Coins';

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
        
        //Top 10 Coin Alert
        $respons = Curl::to('https://api.coinmarketcap.com/v1/ticker/?limit=10&sort=rank')->withContentType('application/json')->get();               
        $datas = json_decode($respons);   
        $text = "<b>Top fiat coin in 24h</b>".chr(10);
        $i = 1;
        foreach($datas as $data){  
            $text.=$i." <b>".$data->symbol."</b>: $". number_format($data->price_usd,($data->price_usd >1)?2:4,'.',',')."(".$data->percent_change_24h ."&#37;" .(($data->percent_change_24h > 0)? "&#9650;" : "&#128315;"). ")".chr(10);                  
            $i++;
        } 
        // $text.='View all listed coins at <a href="https://icoincryptos.com/coins">website</a>'.chr(10).chr(10);

        // foreach ( $chat_ids as $chat_id) {        
            Helper::sendMessage('-348068575', $text,false,1,false);                  
        // }
    }
}
