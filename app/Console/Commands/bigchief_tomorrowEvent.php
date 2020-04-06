<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Model\Telegram_chat;
use \App\Model\Events;
use Carbon\Carbon;
use Shortener; 
use Helper;

class bigchief_tomorrowEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tomorrow:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tomorrow Event Alert';

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
        
        //Tomorrow's Event Alert
        $text="<b>Tomorrow's events (".Carbon::tomorrow()->format('M d')."):</b>".chr(10);                
        $tomorrows_events = Events::where('event_start_date', 'like', Carbon::tomorrow()->format('Y-m-d') . '%')->where('event_approved', '=', 1)                        
                ->where('event_deleted', '=', 0)
                ->orderBy('event_start_date', 'asc')
                ->take(10)                        
                ->get();                        
        foreach ($tomorrows_events as $event) {
            $url=asset('/').'event/'.$event->id;
            $url=Shortener::shorten("$url");
            $googleLink = Shortener::shorten(Helper::getGoogleLink($event->id));
            $appleLink = Shortener::shorten(asset('/').'event-link/'.$event->id);
            $text.=$event->event_title.chr(10).$url.chr(10);
            $text.="Add to calendar : <a href='".$googleLink."'>Google</a> | <a href='".$appleLink."'>Apple</a>".chr(10).chr(10);
        }                        
        if(!$tomorrows_events->isEmpty()){
            // $text.='View more events at <a href="https://icoincryptos.com/events">website</a>'.chr(10);                 
            // $text.='Join us @icoincryptos'.chr(10);
            // foreach ( $chat_ids as $chat_id) {        
                Helper::sendMessage('-348068575', $text, false, 1, 1);
            // } 
        }
        
    }
}
