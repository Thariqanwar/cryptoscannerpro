<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use Shortener; 
use Helper;

class bigchief_newsAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'News Alert';

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
        //News Alert
        $response = Curl::to('https://min-api.cryptocompare.com/data/v2/news/?lang=EN')->withContentType('application/json')->get();
        $data     = json_decode($response);                  
        $i = 1;    
        if($data){
            foreach($data->Data as $news){                     
                $text='<b>'.$news->title.'</b>'.chr(10);   
                $body = substr($news->body, 0, 150);
                $text.=$body.'... <a href="'. Shortener::shorten("$news->url") .'">read more</a>'.chr(10);                            
                    Helper::sendMessage('-348068575', $text, false, 1, false);
                    // if($i == 5){
                    //     $text='View more upto date news at <a href="https://icoincryptos.com/news">website</a>'.chr(10);
                    //     Helper::sendMessage('-348068575', $text, false, 1, false);
                    // }               
                if($i == 5){                
                    break;
                }
                $i++;                    
            }
        }
    }
}
