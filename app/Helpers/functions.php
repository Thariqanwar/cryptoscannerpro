<?php 
namespace App\Helpers;
use Illuminate\Http\Request;
use App\Model\Icos;
use Ixudra\Curl\Facades\Curl;
use \App\Model\Coins;
use \App\Model\Category;
use \App\Model\Event_category;
use \App\Model\Events;
use \App\Model\Vote;
use \App\Model\IcoVote;
use \App\Model\Userlist;
use \App\Model\Blog;
use \App\Model\Comments;
use \App\Model\IcoComment;
use \App\Model\IcoRating;
use \App\Model\Telegram_chat;
use \App\Model\Country;
use \App\Model\Advertisement;
use \App\Model\Pivot;
use \App\Model\Wall;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use DateTime;
use Spatie\CalendarLinks\Link;
use Shortener;

class AppHelperBc 
{
	//Limiting words
	public static function limit_string($string,$length){
            $string = strip_tags($string);
            if (strlen($string) > $length) {
                $stringCut = substr($string, 0, $length);
                $string = trim($stringCut).'...'; 
            }
            echo $string;
	}

        public static function string_limit($string,$length){
            $string = strip_tags($string);
            if (strlen($string) > $length) {
                $stringCut = substr($string, 0, $length);
                $string = trim($stringCut).'...'; 
            }
            return $string;
	}
        
        //Conver utf-8 to emoji
        public static function getEmoji($utf_code){
            $pattern = '@\\\x([0-9a-fA-F]{2})@x';
            $emoji = preg_replace_callback(
              $pattern,
              function ($captures) {
                return chr(hexdec($captures[1]));
              },
              $utf_code
            );
            return $emoji;
        }


        //Get single coin details
	public static function getCoin($id){
            return Coins::where('id','=',$id)->first();
	}

	//get coin price using api
	public static function getCoinPrice($coin_symbol){
            $response = Curl::to('https://min-api.cryptocompare.com/data/pricemultifull?fsyms='.$coin_symbol.'&tsyms=USD')->withContentType('application/json')->get();
            $data = json_decode($response);
            if(!empty($data->Data)){
                foreach ($data as $key => $coins) {
                    $coins=json_decode(json_encode($coins),true);
                    foreach($coins as $coin){
                            print_r($coin['USD']['PRICE']);
                            echo '('.round($coin['USD']['CHANGE24HOUR'],2).')';
                            break;
                    }
                    break;
                }
            }
	}

	//get event categories
	public static function getEventCategory($id){
            return Event_category::where('id','=',$id)->first();
	}
        
        //get count of event - categorory  base
        public static function getEventCategoryCount($event_cat_id){            
            $count = Events::where('event_approved', '=', '1')        
                ->where('event_category', 'like', '%"'.$event_cat_id.'"%')
                ->count();
            return $count;
	}	        
        
        //get ICO category by id
	public static function getIcoCategory($id){
            return Category::where('id','=',$id)->first();
	}
        
        //get count of ICO - categorory  base
        public static function getIcoCategoryCount($ico_cat_id){                                    
            $count = Icos::where('ico_status', '=', '1')         
                ->where('ico_category', 'like', '%"'.$ico_cat_id.'"%') 
                ->count();  
            return $count;
        }
        
	// Get User details 
	public static function user($id){
            return Userlist::where('id','=',$id)->first();
	}

	//Get Next/previous Blog
	public static function blogNextPre($id){
            $blog=array();
	    // get the current user
	    $blog_obj = Blog::find($id);
	    // get previous user id
	    $blog['pre'] = Blog::where('blog_id', '<', $blog_obj->blog_id)->where('blog_approval','=',1)->max('blog_id');
	    // get next user id
	    $blog['next'] = Blog::where('blog_id', '>', $blog_obj->blog_id)->where('blog_approval','=',1)->min('blog_id');
	    return $blog;
	}


	//get event voting details
	public static function getVoteDetails($id){
            $details= array();
            $totalvotes = Vote::where('event_id','=',$id)->count();
            $lvotes=Vote::where('event_id','=',$id)->where('vote','=','1')->count();
            $dvotes=Vote::where('event_id','=',$id)->where('vote','=','0')->count();

            if($totalvotes > 0){
                $details['totalvotes'] = $totalvotes.' Votes';                                                                  
                $details['likper'] = round((($lvotes > 0)?($lvotes/$totalvotes)*100:0),2);
                $details['dislikper'] = round((($dvotes > 0)?($dvotes/$totalvotes)*100:0),2);
            }else{
                $details['totalvotes'] = 'be the first voter';
                $details['likper'] = 0;
                $details['dislikper'] = 0;
            }                        
            return $details;
	}

	//checking is voted using ip 
	public static function isVoted($id){
            $ip = request()->ip();
            if($ip=="::1"){
                $ip= "127.0.0.1";
            }else{
                $ip=$ip;
            }
            $exist=Vote::where('event_id','=',$id)
                ->where('ip','=',$ip)
                ->exists();
            return $exist;
	}
        
        //checking is voted using ip 
	public static function isIcoVoted($id){
            $ip = request()->ip();
            if($ip=="::1"){
                $ip= "127.0.0.1";
            }
            
            $exist=IcoVote::where('ico_id','=',$id)
                ->where('ip','=',$ip)
                ->exists();
            return $exist;
	}
        
        public static function getIcoVote($id){
            $ip = request()->ip();
            if($ip=="::1"){
                $ip= "127.0.0.1";
            }
            
            $IcoVote = IcoVote::where('ico_id','=',$id)
                ->where('ip','=',$ip)
                ->value('vote');
                //->pluck('vote');
            return $IcoVote ;
	}
        
        //get event voting details
	public static function getIcoVoteDetails($id){
		$details= array();
		$total_votes = IcoVote::where('ico_id','=',$id)->count();
		$likes =IcoVote::where('ico_id','=',$id)->where('vote','=','1')->count();
		$dislikes=IcoVote::where('ico_id','=',$id)->where('vote','=','0')->count();
		$return = array();
                $return['lpercent'] = '<i class="fa fa-thumbs-up" aria-hidden="true"></i> '.$likes;
                $return['dpercent'] = '<i class="fa fa-thumbs-down" aria-hidden="true"></i> '.$dislikes;
                return $return;
                
                if($total_votes>0){
                    $lpercent=($likes>0)?($likes/$total_votes)*100:0;
                    $dpercent=($dislikes>0)?($dislikes/$total_votes)*100:0;
                    $return['lpercent'] = round($lpercent,2);
                    $return['dpercent'] = round($dpercent,2);
                }else{
                    $return['comment'] = 'be the first';
                }
                return $return;
	}
        
        public static function getIcoRatingDetails($ico_id) {
            $rating = [];
            $total_rating = IcoRating::where('exp_ico_id','=',$ico_id)->sum('exp_rating');
            $rating_count = IcoRating::where('exp_ico_id','=',$ico_id)->count();
            $user_array = IcoRating::select('exp_user_id')->where('exp_ico_id','=',$ico_id)->groupBy('exp_user_id')->get();
            $expert_count = $user_array->count();             
            $product_count = IcoRating::where('exp_ico_id','=',$ico_id)->where('exp_category','=','product')->count();
            $product_sum = IcoRating::where('exp_ico_id','=',$ico_id)->where('exp_category','=','product')->sum('exp_rating');
            $product_rating = ($product_count>0)?($product_sum/$product_count):'N/A';
            $vision_count = IcoRating::where('exp_ico_id','=',$ico_id)->where('exp_category','=','vision')->count();
            $vision_sum = IcoRating::where('exp_ico_id','=',$ico_id)->where('exp_category','=','vision')->sum('exp_rating');
            $vision_rating = ($vision_count>0)?($vision_sum/$vision_count):'N/A'; 
            $team_count = IcoRating::where('exp_ico_id','=',$ico_id)->where('exp_category','=','team')->count();
            $team_sum = IcoRating::where('exp_ico_id','=',$ico_id)->where('exp_category','=','team')->sum('exp_rating');
            $team_rating = ($team_count>0)?($team_sum/$team_count):'N/A';
            $profile_count = IcoRating::where('exp_ico_id','=',$ico_id)->where('exp_category','=','profile')->count();
            $profile_sum = IcoRating::where('exp_ico_id','=',$ico_id)->where('exp_category','=','profile')->sum('exp_rating');
            $profile_rating = ($profile_count>0)?($profile_sum/$profile_count):'N/A';
            $rating['total_rating'] = round(($rating_count >0) ? $total_rating/$rating_count : 0,1);
            $rating['expert_count'] = $expert_count;
            $rating['product_rating'] = $product_rating;
            $rating['vision_rating'] = $vision_rating;
            $rating['team_rating'] = $team_rating;
            $rating['profile_rating'] = $profile_rating;
            return $rating;
        }
        
        public static function getIcoSubRating($ico_id, $category) {
            $rating = IcoRating::where('exp_ico_id','=',$ico_id)
                ->where('exp_user_id','=',Auth::user()->id)
                ->where('exp_category','=',$category)
                ->value('exp_rating');
            return $rating;
        }

	//checking is commented using ip 
	public static function isCommented($id){
            $ip = request()->ip();
            if($ip=="::1"){
                $ip= "127.0.0.1";
            }else{
                $ip=$ip;
            }
            $exist=Comments::where('comment_event_id','=',$id)
                ->where('comment_ip','=',$ip)
                ->exists();
            return $exist;

	}

	//get average rating
	public static function averageRating($id){		
            return Comments::where('comment_event_id','=',$id)
                ->avg('comment_rating');
	}

	//get upcoming events
	public static function upcomingEvents(){
            $upcoming_events     = Events::where('event_start_date', '>', Carbon::now()->format('Y-m-d H:i:s'))->where('event_approved', '=', 1)->where('event_deleted', '=', 0)->take(3)->get();
            return $upcoming_events;
	}

	//get all coins
	public static function getCoins(){
            return Coins::all();            
	}
        
        public static function getRewardCoins(){
            return Coins::where('coin_type','=',1)->get();
        }
        
        //get all Event categories
	public static function getCountry(){
            return Country::all();
	}
        
        //get all Event categories
	public static function getCountryById($id){
            return Country::where('id','=',$id)->first();
	}
        
	//get all Event categories
	public static function getCategories(){
            return Event_category::where('cat_status','=',1)->get();
	}
        
        //get Date Diff
        public static function dateDiff($date){
            $now = Carbon::now();
            $end_date = Carbon::parse($date);
            $lengthOfAd = $end_date->diffInDays($now);                       
            return $lengthOfAd ;             
        }                

        //get all ICO categories
	public static function getIcoCategories(){
            return Category::where('cat_status','=',1)->get();
	}
        
	//get Replys
	public static function getReplys($comment_id,$event_id){
            return Comments::where('comment_event_id','=',$event_id)
		->where('comment_parent','=',$comment_id)
		->get();
	}

        //get Replys
	public static function getIcoReplys($comment_id,$ico_id){
            return IcoComment::where('comment_ico_id','=',$ico_id)
		->where('comment_parent','=',$comment_id)
		->get();
	}

	// Get Google link 
	public static function getGoogleLink($id){
            $obj=Events::where('id','=',$id)->first();
            
            $date=date_create($obj->event_start_date);
            $frm=date_format($date,"Y-m-d H:i");

            $date1=date_create($obj->event_end_date);
            $t=date_format($date1,"Y-m-d H:i");

            $from = DateTime::createFromFormat('Y-m-d H:i', $frm);
            $to = DateTime::createFromFormat('Y-m-d H:i',$t);

            $link = Link::create($obj->event_title,$from, $to)
                ->description($obj->event_detail);
            if($obj->event_location){
                $link->address($obj->event_location);
            }

            return $link->google();		
	}
        
        // Get Google link 
	public static function getIcoGoogleLink($id){            
            $obj=Icos::where('id','=',$id)->first();

            $date=date_create($obj->ico_start_date);
            $frm=date_format($date,"Y-m-d H:i");

            $date1=date_create($obj->ico_end_date);
            $t=date_format($date1,"Y-m-d H:i");

            $from = DateTime::createFromFormat('Y-m-d H:i', $frm);
            $to = DateTime::createFromFormat('Y-m-d H:i',$t);

            $link = Link::create($obj->ico_name,$from, $to)
                ->description($obj->ico_description);

            return $link->google();		
	}

	//Get Ical link
	public static function getIcalLink($id){
            $obj=Events::where('id','=',$id)->first();
            //echo $obj->event_start_date;

            $date=date_create($obj->event_start_date);
            $frm=date_format($date,"Y-m-d H:i");

            $date1=date_create($obj->event_end_date);
            $t=date_format($date1,"Y-m-d H:i");

            $from = DateTime::createFromFormat('Y-m-d H:i', $frm);
            $to = DateTime::createFromFormat('Y-m-d H:i',$t);

            $link = Link::create($obj->event_title,$from, $to)
                ->description($obj->event_detail);
            if($obj->event_location){
                $link->address($obj->event_location);
            }
            return $link->ics();		
	}
        
        //Get Ical link For ICO
        public static function getIcoIcalLink($id){            
            $obj=Icos::where('id','=',$id)->first();

            $date=date_create($obj->ico_start_date);
            $frm=date_format($date,"Y-m-d H:i");

            $date1=date_create($obj->ico_end_date);
            $t=date_format($date1,"Y-m-d H:i");

            $from = DateTime::createFromFormat('Y-m-d H:i', $frm);
            $to = DateTime::createFromFormat('Y-m-d H:i',$t);

            $link = Link::create($obj->ico_name,$from, $to)
                ->description($obj->ico_description);

            return $link->ics();		
	}

	//Send Event Message through Bot Tomorrow
	public static function sendEventTelegramMsg(){
                $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");	
                $text="<b>Tomorrow's events (".Carbon::tomorrow()->format('M d')."):</b>".chr(10);                	

		//Events List
		$tomorrow_events = Events::where('event_start_date', 'like', Carbon::tomorrow()->format('Y-m-d') . '%')->where('event_approved', '=', 1)->where('event_deleted', '=', 0)->get();
		foreach ($tomorrow_events as $event) {
                    $url=asset('/').'event/'.$event->id;
                    $url=Shortener::shorten("$url");
                    $googleLink = Shortener::shorten(Helper::getGoogleLink($event->id));
                    $appleLink = Helper::getIcalLink($event->id);
                    $text.=$event->event_title.chr(10).$url.chr(10);
                    $text.="Add to calendar : <a href='".$googleLink."'>Google</a> | <a href='".$appleLink."'>Apple </a>".chr(10).'----------'.chr(10).chr(10);
		}
		if($tomorrow_events->isEmpty()){
                    $text.='No Events scheduled for tomorrow'.chr(10).chr(10);
		}
		//$text=urlencode($text);
		// $data = [
		//     'chat_id' => '591661415',
		//     'text' => $text
		// ];
		
		\App\Helpers\AppHelperBc::updateChatId();
		
            //Sending messages to telegram bot
            $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
            foreach ( $chat_ids as $key => $chat_id) {
        	//echo $chat_id->chat_id.'<br/>';
        	if($tomorrow_events->isEmpty()){			
                    $data = [
                        'chat_id' => $chat_id->chat_id,
                        'text' => $text,
                        'parse_mode'=>'html'
                    ];
                }else{
                    $keyboard = [
                        'inline_keyboard' => [
                            [
                                ['text' => "Real \xF0\x9F\x91\x8D", 'callback_data' => '1'],
                                ['text' => "Fake \xF0\x9F\x91\x8E", 'callback_data' => '-1']
                            ]
                        ]
                    ];
                    $encodedKeyboard = json_encode($keyboard);
                    $data = [
                        'chat_id' => $chat_id->chat_id,
                        'text' => $text,
                        'parse_mode'=>'html',
                        'reply_markup' => $encodedKeyboard
                    ];
                }
        	
                $ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);
                //return $data;
                      // try {
                      //     $response = file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );

                      // }
                      // catch (Exception $e) {
                      //     echo $e->getMessage();
                      // }						
            }       		
            //$response = file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
            return $chat_ids;
	}

	//Send Event Message through Bot Upcoming
	public static function sendUpEventTelegramMsg(){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $text="<b>Upcoming Events</b>".chr(10);

            //Upcoming Events List Url
            $url=asset('/').'events';
            $url=Shortener::shorten("$url");
            $text .= $url;

            \App\Helpers\AppHelperBc::updateChatId();
		
            //Sending messages to telegram bot
            $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
            foreach ( $chat_ids as $key => $chat_id) {
        	//echo $chat_id->chat_id.'<br/>';
        	$data = [
                    'chat_id' => $chat_id->chat_id,
                    'text' => $text,
                    'parse_mode'=>'html'
                ];

                $ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);
            }
            return $chat_ids;
	}

	//Send Icos to Telegram Upcoming
	public static function sendUpIcosTelegramMsg(){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $text="<b>Upcoming Icos</b>".chr(10);
		
            //Upcoming Events List Url
            $url=asset('/').'ico-upcoming-list';
            $url=Shortener::shorten("$url");
            $text .= $url;

            \App\Helpers\AppHelperBc::updateChatId();
		
            //Sending messages to telegram bot
            $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
            foreach ( $chat_ids as $key => $chat_id) {
        	//echo $chat_id->chat_id.'<br/>';
        	$data = [
                    'chat_id' => $chat_id->chat_id,
                    'text' => $text,
                    'parse_mode'=>'html'
                ];

                $ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);
            }
            return $chat_ids;
	}

	//Send Icos to Telegram Active
	public static function sendActiveIcosTelegramMsg(){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $text="<b>Active Icos</b>".chr(10);

            //Upcoming Events List Url
            $url=asset('/').'ico-active-list';
            $url=Shortener::shorten("$url");
            $text .= $url;

            \App\Helpers\AppHelperBc::updateChatId();
		
            //Sending messages to telegram bot
            $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
            foreach ( $chat_ids as $key => $chat_id) {
        	//echo $chat_id->chat_id.'<br/>';
        	$data = [
                    'chat_id' => $chat_id->chat_id,
                    'text' => $text,
                    'parse_mode'=>'html'
                ];

                $ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);
            }
            return $chat_ids;
	}

	//Send Icos to Telegram Closingsoon
	public static function sendClosingsoonIcosTelegramMsg(){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $text="<b>Icos Closing Soon</b>".chr(10);

            //Upcoming Events List Url
            $url=asset('/').'ico-closing-soon';
            $url=Shortener::shorten("$url");
            $text .= $url;

            \App\Helpers\AppHelperBc::updateChatId();
		
            //Sending messages to telegram bot
            $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
            foreach ( $chat_ids as $key => $chat_id) {
        	//echo $chat_id->chat_id.'<br/>';
        	$data = [
                    'chat_id' => $chat_id->chat_id,
                    'text' => $text,
                    'parse_mode'=>'html'
                ];

                $ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);
            }
            return $chat_ids;
        }

	//Send blog
	public static function sendBlogTelegramMsg($id){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");		
            $blog_details=Blog::where('blog_id', '=', $id)
                                ->where('blog_approval','=',1)
                                ->first();
        
            $url=asset('/').'blog/'.$blog_details->blog_id;
            $url=Shortener::shorten("$url");               
            $text='<b>'.$blog_details->blog_head.'</b>'.chr(10).$url.chr(10);

            \App\Helpers\AppHelperBc::updateChatId();
		
        
            $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
            foreach ( $chat_ids as $key => $chat_id) {
        	$data = [
                    'chat_id' => $chat_id->chat_id,
                    'text' => $text,
                    'parse_mode'=>'html'
                ];
                $ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);			
            }        		
            //$response = file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
            return $chat_ids;
	}
        
        //Send Advertisement To Telegram 
        public static function sendAdvertisementToTelegram($id,$test_case = false){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");		
            $adv_details=Advertisement::where('id', '=', $id)->first();                              
            $body = \App\Helpers\AppHelperBc::string_limit($adv_details->adv_description, 1400);
                        
            $text='<b>'.$adv_details->adv_title.'</b>'.chr(10); 
            $text.='<a href="https://icoincryptos.com/public/uploads/advertise'.$adv_details->adv_image.'">&#8205;</a>'.chr(10);                
            $text.=$body.chr(10).$adv_details->adv_link.chr(10);    
            if($test_case){
                \App\Helpers\AppHelperBc::sendMessage('-1001280231567', $text);   
            }else{
                $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
//                \App\Helpers\AppHelperBc::sendMessage('677069447', $text); 
                foreach ( $chat_ids as $chat_id) {        
                   \App\Helpers\AppHelperBc::sendMessage($chat_id->chat_id, $text);                  
                }  
            }
            return;
	}
        
	//Send Results for telegram commands
	public static function sendMessage ($chatId, $message, $reply = false, $mute = false, $inst_view = false,$keyboard = false) {
            $botToken = env("TELEGRAM_API", "1060606546:AAHiwqRStwOSYl0TDmVFmliImtVrvR2Zf4Y");
            $website = "https://api.telegram.org/bot".$botToken;
            $url = $website."/sendMessage?chat_id=".$chatId."&parse_mode=HTML&text=".urlencode($message);  
            if($reply){
                $url .= "&reply_to_message_id=$reply";
            }
            if($mute){
                $url .= "&disable_notification=1";
            } 
            if($inst_view){
                $url .= "&disable_web_page_preview=1";
            }            
            if($keyboard){
                $url .= "&reply_markup=".json_encode($keyboard);
            }
            
//          file_get_contents($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            curl_close($ch);  
            return $data;
	}
        
        //Edit/Update Message
        public static function editMessage($postfields){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $url = "https://api.telegram.org/bot".$botToken."/editMessageText";           
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            curl_close ($ch);   
            return $data;
        }

        //Send Video Results for telegram commands
	public static function sendVideo ($chatId, $video, $reply = false, $mute = false) {
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $website = "https://api.telegram.org/bot".$botToken;
            $url = $website."/sendVideo?chat_id=".$chatId."&parse_mode=HTML&video=".$video;  
            if($reply){
                $url .= "&reply_to_message_id=$reply";
            }
            if($mute){
                $url .= "&disable_notification=1";
            } 
//          file_get_contents($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            curl_close($ch);  
            return $data;
	}
        
        //Send Animation Results for telegram commands
	public static function sendAnimation ($chatId, $animation, $reply = false, $mute = false) {
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $website = "https://api.telegram.org/bot".$botToken;
            $url = $website."/sendAnimation?chat_id=".$chatId."&parse_mode=HTML&animation=".$animation;  
            if($reply){
                $url .= "&reply_to_message_id=$reply";
            }
            if($mute){
                $url .= "&disable_notification=1";
            } 
//          file_get_contents($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            curl_close($ch);  
            return $data;
	}        

	// Set/Reset Webhook for telegram
	public static function setwebhook(){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $website = "https://icoincryptos.com/icoincrypto_commands";
            $url = "https://api.telegram.org/bot$botToken/setwebhook?url=$website";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            curl_close($ch);
	}
        
	public static function deletewebhook(){
            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
            $url = "https://api.telegram.org/bot$botToken/setwebhook?url=";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            curl_close($ch);
	}
        
        //Saving Telegram chat id's
        public static function updateTelegramChatId($content){
            $contents = json_decode($content, TRUE);
            
            $chatId = $contents['message']['chat']['id'];

            $exist=Telegram_chat::where('chat_id','=',"$chatId")->exists();
            if(!$exist){
                $obj=new Telegram_chat();
                $obj->chat_id="$chatId";
                if($contents['message']['chat']['type']=='private'){
                    $obj->chat_name=$contents['message']['chat']['first_name'];
                }else{
                    $obj->chat_name=$contents['message']['chat']['title'];
                }
                if(array_key_exists("username",$contents['message']['chat'])){
                    $obj->chat_user = '@'.$contents['message']['chat']["username"];
                }
                $obj->chat_type= $contents['message']['chat']['type'];
                $obj->chat_approve=1;                
                $obj->save();
            }else{
                if(array_key_exists("left_chat_participant",$contents["message"])){
                    $left_chat_id = $contents["message"]["left_chat_participant"]["id"];     
                    if($left_chat_id == '638669410'){
                        Telegram_chat::where('chat_id', "$chatId")                        
                            ->update(['chat_approve' => 0]);
                    }                    
                }
                if(array_key_exists("new_chat_participant",$contents["message"])){
                    $new_chat_id = $contents["message"]["new_chat_participant"]["id"];     
                    if($new_chat_id == '638669410'){
                        Telegram_chat::where('chat_id', "$chatId")                        
                            ->update(['chat_approve' => 1]);
                    }                    
                }
                if(array_key_exists("username",$contents['message']['chat'])){                    
                    Telegram_chat::where('chat_id', "$chatId")                        
                        ->update(['chat_user' => '@'.$contents['message']['chat']["username"]]);                                      
                }
            }           
            return;
        }
        
        //RSI Calculation        
        public static function calculateRSI($datas){             
            $j = $k = 1; $count = ($datas)?count($datas):0;
            $period = 0.07142857; // 1/period  1/14    (RSI period)
            
            $change = $gain = $loss = $avg_gain = $avg_loss = $rs = $rsi = array();     
            if($datas){ 
                foreach($datas as $key => $data){                             
                    if(count($datas)< 15)
                        break;
                    $change[$key] = $datas[$j][4] - $data[4];      
                    $gain[$key] = ($change[$key] > 0 )? $change[$key] : 0;
                    $loss[$key] = ($change[$key] < 0 )? abs($change[$key]) : 0;     
                    if($key == 13){
                        $avg_gain[0] = (array_sum($gain)/14);
                        $avg_loss[0] = (array_sum($loss)/14);
                        if($avg_loss[0] > 0){
                            $rs[0] = $avg_gain[0]/$avg_loss[0];
                        }else{
                            $rs[0] = 0;
                        }
                        if($avg_loss[0] > 0){
                            $rsi[0] = 100 - (100/(1+$rs[0]));
                        }else{
                            $rsi[0] = 100;
                        }
                    }
                    if($key > 13){
                        $avg_gain[$k] = $gain[$key]*$period+(1-$period)*$avg_gain[$k-1];
                        $avg_loss[$k] = $loss[$key]*$period+(1-$period)*$avg_loss[$k-1];                    
                        if($avg_loss[$k] > 0){
                            $rs[$k] = $avg_gain[$k]/$avg_loss[$k];
                            $rsi[$k] = 100 - (100/(1+$rs[$k]));
                        }else{
                            $rsi[$k] = 100;
                        }
                        $k++;
                    }
                    if($j == ($count-1))
                        break;
                    $j++; 
                }
            }
            return $rsi;
        }
        
        //MACD Calculation
        public static function calculateMACD($datas){  
            $result = array();
            if($datas){
                $fast_ema_value = 2/(12+1);
                $slow_ema_value = 2/(26+1);
                $signal_value = 2/(9+1);
                $i = $j = $k = $m = $n = 1;
                $fast_ema = $slow_ema = $fast_ema_array = $slow_ema_array =  $difference = $signal = $histogram = array();            
                foreach($datas as $key => $data){                             
                    if(count($datas)< 26)
                        break;
                    $fast_ema_array[] = $slow_ema_array[] = $data[4];     
                    if($key == 11){                    
                       $fast_ema[0] = (array_sum($fast_ema_array)/12);
                       unset($fast_ema_array);                                 
                    }

                    if($key > 11){
                       $fast_ema[$i] = (($data[4] - $fast_ema[$i-1]) *$fast_ema_value)+$fast_ema[$i-1];                   

                       if($key == 25){
                            $slow_ema[0] = (array_sum($slow_ema_array)/26);
                            $difference[0] = $fast_ema[$i] - $slow_ema[0];
                            unset($slow_ema_array);
                       }
                       if($key > 25){
                            $slow_ema[$j] = (($data[4] - $slow_ema[$j-1]) *$slow_ema_value)+$slow_ema[$j-1];
                            $difference[$k] = $fast_ema[$i] - $slow_ema[$j];  
                            if($k == 8){
                                $signal[0] = (array_sum($difference)/9);
                                $histogram[0] = $difference[$k] - $signal[0];
                            }
                            if($k > 8){
                                $signal[$m]  = (($difference[$k] - $signal[$m-1]) * $signal_value) + $signal[$m-1];
                                $histogram[$n] = $difference[$k] - $signal[$m];
                                $m++;
                                $n++;
                            }
                            $j++;
                            $k++;                                                
                       }
                       $i++;
                    }                                                                                              
                }
                if(empty($histogram) || count($histogram)< 2){
                    $histogram = array();
                    return $histogram;
                }

                $result = array(
                    'difference' => $difference,
                    'signal' => $signal,
                    'histogram' => $histogram,
                );
                unset($fast_ema);unset($slow_ema);unset($difference);unset($signal);unset($histogram);
            }
            return $result; 
        }
        
        //SMA & EMA Calculation
        public static function calculateSMA_EMA($datas,$period){
            if(count($datas) > $period){
                $sma_array = $sma = $ema = array();
                $i = 0; 
                $sm_const = number_format(2/($period+1),4,'.','');
                foreach($datas as $key => $data){   
                    
                    $sma_array[] = $data[4];                
                    if($key >= $period){
                       array_shift($sma_array);
                       $sma[$i] = (array_sum($sma_array)/$period);   
                       if($key == $period){
                         $ema[$i] = $sma[$i];                     
                       }else{
                         $ema[$i] =  number_format($sm_const*($data[4] - $ema[$i-1])+$ema[$i-1],8,'.','');
                       }
                       $i++;                   
                    }
                }                
                $result = array(
                    'sma' => ($data[4] > end($sma))? 1: 2,
                    'ema' => ($data[4] > end($ema))? 1: 2
                );
                return $result;
            }else{               
                return 0;
            }
        }
        
        //Calculate Adx
        public static function calculateADX($datas){  
            $high = $low = $close = $volume = array();            
            foreach($datas as $data){  
                $high[] = $data[2];
                $low[]  = $data[3];                         
                $close[] = $data[4];  
                $volume[] = $data[5];  
            }
            $adx = trader_adx($high, $low, $close,14); 
            $adx_m_di = trader_minus_di($high, $low, $close,14); 
            $adx_p_di = trader_plus_di($high, $low, $close,14); 
            $aroon  =  trader_aroon($high, $low,14);
            $stoch = trader_stoch($high, $low, $close,14,1,null,3);
            $sar = trader_sar($high, $low, 0.02,0.20); //, 0.02,0.20
            $mfi = trader_mfi($high, $low, $close,$volume,14);
            $rsi = trader_rsi($close,14);
            $result = array(
                'adx' => $adx,
                'adx_m_di' => $adx_m_di,
                'adx_p_di' => $adx_p_di,
                'aroon' => $aroon,
                'stoch' => $stoch,
                'sar' => $sar,
                'mfi' => $mfi,
                'rsi' => $rsi
            );
            return $result;
        }
        
        //Bollinger Band Calculation
        public static function calculateBB($datas){
            $i = 0;
            if($datas){
                foreach($datas as $key => $data){                    
                    if(count($datas) < 20)
                        break;
                    $sma_array[] = $data[4];     
                    if($key >= 20){
                       array_shift($sma_array);
                       $fast_ema[$i] = (array_sum($sma_array)/20);   
                       $stdev = AppHelperBc::standard_deviation($sma_array);
                       $upper[] = $fast_ema[$i]+($stdev*3);
                       $lower[] = $fast_ema[$i]-($stdev*3);
                       $i++;
                    }                
                }      
            }
            $result = array(
                'close' => (isset($sma_array))? end($sma_array) : 0,
                'upper' => (isset($upper))? number_format(end($upper),4,'.',''):0,
                'lower' => (isset($lower))? number_format(end($lower),4,'.',''):0
            );
            return $result;
        }
        
        static function standard_deviation($sma, $sample = false){
            $fMean = array_sum($sma) / count($sma);
            $fVariance = 0.0;
            foreach ($sma as $i){
                $fVariance += pow($i - $fMean, 2);
            }
            $fVariance /= ( $sample ? count($sma) - 1 : count($sma) );
            return (float) sqrt($fVariance);
        }
        
        //Pivot, Resistance and Support Checking
        public static function getPivotTxt($symbol,$lastPrice){ 
            return;            
            $pivot = Pivot::where('symbol','=',"$symbol")->first();                       
            
            if($pivot && $lastPrice > 0.00001){  
                $pivotR1 = Wall::where('wall_symbol','=',"$symbol")->where('wall_type','=',7)->first();  
                $pivotR2 = Wall::where('wall_symbol','=',"$symbol")->where('wall_type','=',8)->first();  
                $pivotS1 = Wall::where('wall_symbol','=',"$symbol")->where('wall_type','=',9)->first();  
                $pivotS2 = Wall::where('wall_symbol','=',"$symbol")->where('wall_type','=',10)->first();
                if($pivot->resistance1 ==  $lastPrice && (!$pivotR1 || $pivotR1->timestamp + 3600 <= strtotime(date('Y-m-d G:i:s')))){ 
                    $text = '<b>'.$symbol.'</b>'.chr(10).'Price Hit R1'.chr(10);
                    $text.= 'Price :'.$lastPrice.chr(10).'R2 :'.$pivot->resistance2.chr(10).'R1 :'.$pivot->resistance1.chr(10) .'PP :'.$pivot->pivot_point.chr(10).'S1 :'.$pivot->support1.chr(10).'S2 :'.$pivot->support2; 
                    AppHelperBc::sendMessage('-1001280231567', $text, false, 1, false );
                    if($pivotR1){
                        $r1_obj = Wall::find($pivotR1->id);
                    }else{
                        $r1_obj = new Wall();
                    }                                        
                    $r1_obj->wall_symbol = $symbol;
                    $r1_obj->wall_type = 7;                            
                    $r1_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                    $r1_obj->save(); 
                }else if($pivot->resistance2 == $lastPrice && (!$pivotR2 || $pivotR2->timestamp + 3600 <= strtotime(date('Y-m-d G:i:s')))){
                    $text = '<b>'.$symbol.'</b>'.chr(10).'Price Hit R2'.chr(10);                            
                    $text.= 'Price :'.$lastPrice.chr(10).'R2 :'.$pivot->resistance2.chr(10).'R1 :'.$pivot->resistance1.chr(10) .'PP :'.$pivot->pivot_point.chr(10).'S1 :'.$pivot->support1.chr(10).'S2 :'.$pivot->support2;                                                        
                    AppHelperBc::sendMessage('-1001280231567', $text, false, 1, false );
                    if($pivotR2){
                        $r2_obj = Wall::find($pivotR2->id);
                    }else{
                        $r2_obj = new Wall();
                    }                                        
                    $r2_obj->wall_symbol = $symbol;
                    $r2_obj->wall_type = 8;                            
                    $r2_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                    $r2_obj->save(); 

                }else if($pivot->support1 ==  $lastPrice && (!$pivotS1 || $pivotS1->timestamp + 3600 <= strtotime(date('Y-m-d G:i:s')))){   
                    $text = '<b>'.$symbol.'</b>'.chr(10).'Price Hit S1'.chr(10);
                    $text.= 'Price :'.$lastPrice.chr(10).'R2 :'.$pivot->resistance2.chr(10).'R1 :'.$pivot->resistance1.chr(10) .'PP :'.$pivot->pivot_point.chr(10).'S1 :'.$pivot->support1.chr(10).'S2 :'.$pivot->support2; 
                    AppHelperBc::sendMessage('-1001280231567', $text, false, 1, false );
                    if($pivotS1){
                        $s1_obj = Wall::find($pivotS1->id);
                    }else{
                        $s1_obj = new Wall();
                    }                                        
                    $s1_obj->wall_symbol = $symbol;
                    $s1_obj->wall_type = 9;                            
                    $s1_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                    $s1_obj->save(); 
                }else if($pivot->support2 == $lastPrice && (!$pivotS2 || $pivotS2->timestamp + 3600 <= strtotime(date('Y-m-d G:i:s')))){
                    $text = '<b>'.$symbol.'</b>'.chr(10).'Price Hit S2'.chr(10);
                    $text.= 'Price :'.$lastPrice.chr(10).'R2 :'.$pivot->resistance2.chr(10).'R1 :'.$pivot->resistance1.chr(10) .'PP :'.$pivot->pivot_point.chr(10).'S1 :'.$pivot->support1.chr(10).'S2 :'.$pivot->support2; 
                    AppHelperBc::sendMessage('-1001280231567', $text, false, 1, false );
                    if($pivotS2){ 
                        $s2_obj = Wall::find($pivotS2->id);
                    }else{
                        $s2_obj = new Wall();
                    }                                        
                    $s2_obj->wall_symbol = $symbol;
                    $s2_obj->wall_type = 10;                            
                    $s2_obj->timestamp = strtotime(date('Y-m-d G:i:s'));                            
                    $s2_obj->save(); 
                }                                   
            }
        }
        
        
        //Saving chat id's
	public static function updateChatId(){
            return;
//            \App\Helpers\AppHelperBc::deletewebhook();
//            $botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
//            $response1 = Curl::to("https://api.telegram.org/bot$botToken/getUpdates")->withContentType('application/json')->get();
//            \App\Helpers\AppHelperBc::setwebhook();
//            $data = json_decode($response1);
//            $chat_ids=array();
//            $i=0;
//	    foreach ($data as $key => $messages) {
//	    	$messages=json_decode(json_encode($messages),true);
//                if(is_array($messages)){
//                    foreach ($messages as $message) {
//                        if(array_key_exists("message",$message)){
//                            //print_r($message['message']['chat']);
//                            $exist=Telegram_chat::where('chat_id','=',$message['message']['chat']['id'])->exists();
//                            if(!$exist){
//                                $obj=new Telegram_chat();
//                                $obj->chat_id=$message['message']['chat']['id'];
//                                if($message['message']['chat']['type']=='private'){
//                                    $obj->chat_name=$message['message']['chat']['first_name'];
//                                }else{
//                                    $obj->chat_name=$message['message']['chat']['title'];
//                                }                                
//                                $obj->chat_approve=1;
//                                $obj->chat_type= $message['message']['chat']['type'];
//                                $obj->save();
//                            }
//                            $chat_ids[$i++]=$message['message']['chat']['id'];
//                        }
//                    }
//                }	
//	    }
	}
        
        
        
        
        
        
	/*public static function sendIcosTelegramMsg(){
		$botToken = env("TELEGRAM_API", "638669410:AAF0jbejnJbXrY6L9PMdGNXgCgbqh_63AJk");
		$text="<b>Active Icos</b>".chr(10);
		$active_icos = Icos::where('status', '=', 'active')
            ->where('end_date', '>', Carbon::now()->format('Y-m-d H:i:s'))
            ->where('start_date', '<', Carbon::now()->format('Y-m-d H:i:s'))
            ->orderBy('start_date', 'asc')
            ->get();
		foreach ($active_icos as $ico) {
			$url=asset('/').'icos/'.$ico->id;
			$url=Shortener::shorten("$url");
			$start_date=date_create($ico->start_date);
			$end_date=date_create($ico->end_date);
			$duration=date_format($start_date,"Y/m/d").'-'.date_format($end_date,"Y/m/d");
			$text.=$ico->name.chr(10).$url.chr(10).$duration.chr(10).'-----'.chr(10).chr(10);
		}
		if($active_icos->isEmpty()){
			$text.='No Active Icos'.chr(10).chr(10);
		}

		$text.="<b>Upcoming Icos</b>".chr(10);
		$upcoming_icos = Icos::where('status', '=', 'active')
            ->where('start_date', '>', Carbon::now()->format('Y-m-d H:i:s'))
            ->where('start_date', '<', Carbon::now()->addWeeks(1)->format('Y-m-d H:i:s'))
            ->orderBy('start_date', 'asc')
            ->get();
		foreach ($upcoming_icos as $ico) {
			$url=asset('/').'icos/'.$ico->id;
			$url=Shortener::shorten("$url");
			$start_date=date_create($ico->start_date);
			$end_date=date_create($ico->end_date);
			$duration=date_format($start_date,"Y/m/d").'-'.date_format($end_date,"Y/m/d");
			$text.=$ico->name.chr(10).$url.chr(10).$duration.chr(10).'-----'.chr(10).chr(10);
		}
		if($upcoming_icos->isEmpty()){
			$text.='No Upcoming Icos'.chr(10).chr(10);
		}

		

		
		//$text=urlencode($text);
		// $data = [
		//     'chat_id' => '591661415',
		//     'text' => $text
		// ];
		
		$response1 = Curl::to("https://api.telegram.org/bot$botToken/getUpdates")->withContentType('application/json')->get();
        $data     = json_decode($response1);
        $chat_ids=array();
        $i=0;
        foreach ($data as $key => $messages) {
        	$messages=json_decode(json_encode($messages),true);
        		if(is_array($messages)){
        			foreach ($messages as $message) {
        				//print_r($message['message']['chat']);
        				$exist=Telegram_chat::where('chat_id','=',$message['message']['chat']['id'])->exists();
        				if(!$exist){
        					$obj=new Telegram_chat();
        					$obj->chat_id=$message['message']['chat']['id'];
        					if($message['message']['chat']['type']=='private'){
        						$obj->chat_name=$message['message']['chat']['first_name'];
        						
        					}else{
        						$obj->chat_name=$message['message']['chat']['title'];
        					}
        					$obj->chat_approve=1;
        					$obj->save();
        					
        				}
        				$chat_ids[$i++]=$message['message']['chat']['id'];
        			}
        		}	
        }
		
        
        $chat_ids=Telegram_chat::where('chat_approve','=','1')->get();
        foreach ( $chat_ids as $key => $chat_id) {
        	$data = [
			    'chat_id' => $chat_id->chat_id,
			    'text' => $text,
			    'parse_mode'=>'html'
				];
			$response = file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
        }
        
		
		//$response = file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data) );
		return $chat_ids;

	}*/



	// public function SendMail(){

 //    $to='<cordiacetechnologies@gmail.com>' . "\r\n";
 //    $subject='enquiry';
 //    $message= "name:".$enq_name."\n"."phone:".$enq_phone."\n".
 //    $headers =  $request->enq_email;
 //    $x=mail($to,$subject,$message,$headers);
	
	// }
}

?>