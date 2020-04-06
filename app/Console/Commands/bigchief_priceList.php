<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use Helper;

class bigchief_priceList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Price List';

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
        $respons = Curl::to('https://api.coinmarketcap.com/v1/ticker/?limit=10&sort=rank')->withContentType('application/json')->get();               
        $datas = json_decode($respons);   
        $text = '';
        foreach($datas as $data){  
                $text.='<b>'.$data->symbol.'</b> $'.sprintf('%f', $data->price_usd).' | '. $data->price_btc .' &#x20BF;'.chr(10);                   
                $text.='Mcap : $'. number_format($data->market_cap_usd,0,'',',') .chr(10);  
                $text.='Volume : $'.number_format($data->{'24h_volume_usd'},2,'.',',').chr(10);  
                $text.='1h '. $data->percent_change_1h .'&#37; | 24h '. $data->percent_change_24h .'&#37;' .chr(10).chr(10);                           
        } 
        // $text.='View all listed coins at <a href="https://icoincryptos.com/coins">website</a>'.chr(10).chr(10);
        Helper::sendMessage('-348068575', $text, false, 1, 1);
    }
}
