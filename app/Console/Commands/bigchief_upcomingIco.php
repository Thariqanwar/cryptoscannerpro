<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Model\Telegram_chat;
use \App\Model\Icos;
use Carbon\Carbon;
use Shortener; 
use Helper;

class bigchief_upcomingIco extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upcoming:ico';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upcoming Ico Alert';

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
        
        //Upcoming Icos Alert        
        $text="<b>Upcoming Icos:</b>".chr(10);
        $ico_obj= new Icos();
        $upcoming_icos=$ico_obj
                ->where('ico_status', '=', '1')
                ->where('ico_start_date', '>', Carbon::now()->format('Y-m-d H:i:s'))
                ->orderBy('ico_start_date', 'asc')
                ->take(10)
                ->get();
        foreach ($upcoming_icos as $icos) {
            $url=asset('/').'ico-detail/'.$icos->id;
            $url=Shortener::shorten("$url");
            $googleLink = Shortener::shorten(Helper::getIcoGoogleLink($icos->id));
            $appleLink = Shortener::shorten(asset('/').'ico-link/'.$icos->id);
            $text.='<b>'.$icos->ico_name.'</b>'.chr(10).$url.chr(10); 
            $text.='ICO : '.Carbon::parse($icos->ico_start_date)->format('M d').' to '.Carbon::parse($icos->ico_end_date)->format('M d').chr(10);
            $text.="Add to calendar : <a href='".$googleLink."'>Google</a> | <a href='".$appleLink."'>Apple</a>".chr(10).chr(10);
        }
        if(!$upcoming_icos->isEmpty()){
            // $text.='View more ICO\'s at <a href="https://icoincryptos.com/ico-list">website</a>'.chr(10);
            // $text.='Join us @icoincryptos'.chr(10);
            // foreach ( $chat_ids as $chat_id) {        
                Helper::sendMessage('-348068575', $text, false, 1, 1);                  
            // } 
        }
        
    }
}
