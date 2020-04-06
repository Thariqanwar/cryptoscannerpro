<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Subscription;
use App\SubscriptionPeriod;
use App\TimeFrame;
use Auth;
use Redirect;
use Validator;
use App\Feed;
use App\UserFeed;
use App\FeedsCategory;
use App\FeedData;
use SimpleXMLElement;
use App\UserLinks;
use App\UserCategory;
use App\PaymentDetails;
use Mail;
use App\BillingAddress;
use App\Widget;
use App\UserWidgetSetting;
use Ixudra\Curl\Facades\Curl;

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Endroid\QrCode\QrCode;
use App\AlertLog;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit','500M');
       
        $this->middleware(['auth', '2fa']);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function smartTrade()
    {
        return view('user.smart-trade');
    }
    public function portfolio()
    {

        return view('user.portfolio');
    }
    public function screenerpro()
    {
        return view('user.screenerpro');
    }
    public function alerts()
    {
        $alerts=AlertLog::orderby('id','DESC')->get();
        return view('user.alerts')->with('alerts',$alerts);
    }
    public function curl($url,$parameters=null,$headers=null)
    {
        //$url = 'https://min-api.cryptocompare.com/data/top/mktcapfull';
        /*$parameters = [
          'limit'=>'10','tsym'=>'USD'
        ];*/

        $headers = [
          'Accepts: application/json',
          'X-CMC_PRO_API_KEY: cd4f51e2-8060-4ae0-b181-4851d3683d4d'
        ];
        if($parameters!=null)
        {

            $qs = http_build_query($parameters); // query string encode the parameters
            $request = "{$url}?{$qs}"; // create the request URL
        }
        else
        {
            
            $request = "{$url}"; // create the request URL
        }
        $curl = curl_init(); // Get cURL resource
        curl_setopt_array($curl, array(
          CURLOPT_URL => $request,            // set the request URL
          CURLOPT_HTTPHEADER => $headers,     // set the headers 
          CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        //dd(json_decode($response)); // print json decoded response
        curl_close($curl); // Close request
        return $response;
        
    }
    public function curl2($url,$parameters=null,$headers=null)
    {
        //$url = 'https://min-api.cryptocompare.com/data/top/mktcapfull';
        /*$parameters = [
          'limit'=>'10','tsym'=>'USD'
        ];*/

        $headers = [
          'Accepts: application/json',
          'authorization: Apikey fdb6939c2b0580f48e897c76590ecc1c8f8dd777af25201bc756da35d659b026'
        ];
        if($parameters!=null)
        {

            $qs = http_build_query($parameters); // query string encode the parameters
            $request = "{$url}?{$qs}"; // create the request URL
        }
        else
        {
            
            $request = "{$url}"; // create the request URL
        }
        $curl = curl_init(); // Get cURL resource
        curl_setopt_array($curl, array(
          CURLOPT_URL => $request,            // set the request URL
          CURLOPT_HTTPHEADER => $headers,     // set the headers 
          CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        //dd(json_decode($response)); // print json decoded response
        curl_close($curl); // Close request
        return $response;
        
    }
    public function profile2() /*coin market cap*/
    {

       /* $image=public_path().'\telegram\testimage.jpg';
        //print_r($image);
        
         $url = 'https://www.w3schools.com/howto/howto_css_modal_images.asp';
        //$image = 'Images.jpeg';
        $accountKey = 'ee0f48';
        $key="eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tpZCI6IjE1NjMyNTM2NDg0MDctNSIsImVtYWlsIjoiY3J5cHRvc2Nhbm5lcnByb0BnbWFpbC5jb20iLCJzZXJ2aWNlIjoic2NyZWVuLnJpcCIsInBsYW4iOjEwMCwiaWF0IjoxNTYzMjUzNjQ4LCJleHAiOjE1OTQ3ODk2NDh9.MmPRgbb7mdC41AxPTmph89ALyLlqV8xEpeDnwvZ1o6YVg2MUlghoHZPD6usmpoUlNrpjQicNnzy5yEz1qiIQaxXlNYjaX_1TaWSJ3paNXLzroenuPtEZomqGbvPP6OUXstZ1Eq2JOUXuv-9qD0ieXIT9kvFkr0XazoNbY2sHbFON1vMU7wic0cybFVbFr-uFqh2ZncrKTtLwtyI9ZsOOJA3F9ElI-6kj9pJxafnL4mksVRsY7gBB7eYVs1G36_ExNg2mjBafaecXEjaoz3FK6kZFl65hZ7bForej4jDQfFJrhfUSceeMVzdOGqyybInO9Q9eLu1Xutmuc2k0bJyI1obASe1Ju2iRK2uYgGWluvpi9ZXDl5uSgFZhxh_FcX3fKGUFjBQeTx_rw7WLlZW2byXZ6WSTBwBLPJ38kc1HrNqekmxsx3feINHiEvpagyDOCvGuKNVLOh53IaZLmQQscgkgaEYhPuLrzlvDpdzrXQJcfgwS5x6gTKTereuJqZB81e68m4RrVQkYmx06LWw7nmhg8H_cg0ACC1kLc8HlSs0FAXGUxM0Uo8iyd1IETbqOdOkifrtpls0dXS_HuzSbX-dJcNRvVUFx5ZMp9CUu6AkBuh5EcR2rmmHqvJ9p6mkEc-5dhQURzWU-UPTffJsNvA4Cjma2PYvPBsUsbmhwJQg";
        
        //$ch = curl_init('https://screen.rip/capture?token='.$key.'&url='.$url.'&wait=5000');
        $ch = curl_init('http://api.screenshotmachine.com/?key=ee0f48&dimension=1024x786&delay=8000&cacheLimit=0&url='.$url);
        //print_r($ch);
        //$ch = curl_init('http://api.screenshotmachine.com/?key='.$accountKey.'&size=M&format=png&url='.$url);
        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $filename='1';
        $target=public_path().'\telegram\\'.$filename;
        $target.='.jpg';
        copy($image,$target);

        //$r=$this->curl('https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?start=1&limit=5000&convert=USD');
        //$url = 'https://sandbox-api.coinmarketcap.com/v1/global-metrics/quotes/historical?count=1&interval=monthly';
        //$r=$this->curl($url);
        dd('f');*/
       
        $feeds=Feed::All();

        /*Enabled feed list*/
        foreach ($feeds as $key => $feed) {
            if($feed->isFeedEnabled())
            {
                $feed_array[]=$feed->id;
                
            }
            if(!empty($feed_array))
            {
                $new_feeds=FeedData::whereIn('feed_id',$feed_array)->take(20)->orderBy('pub_timestamp','DESC')->get();
            }
            else
            {
                $new_feeds=[];
               //return Redirect::route('UserFeed')->with('fail','You have to enable atleast one feed'); 
            }          
        } 
        
       
        

        //market sentiment
        $res= Curl::to('https://api.alternative.me/fng/')->withContentType('application/json')->get();
             $data=json_decode($res);
             //dd($data);
             $name=$data->name;
            $fear['fear']=$data->data['0']->value;
            $fear['class']=str_replace(' ', '_', $data->data['0']->value_classification);

        //Top 10 Coin Alert
       

        $topcoins = $this->curl('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?limit=10&convert=USD');
        $topcoins = json_decode($topcoins);
        $topcoins=$topcoins->data; 
         //dd($topcoins);
       // dd($volchange);

        
        /*$totalcapusd = Curl::to('https://api.coinmarketcap.com/v1/global/?convert=BTC')->withContentType('application/json')->get();
        $totalcapusd    = json_decode( $totalcapusd);    */             
        //dd($totalcapusd);
        $volume = Curl::to('https://api.binance.com/api/v1/ticker/24hr')->withContentType('application/json')->get();
        $volume = json_decode($volume , TRUE); 
        usort($volume, function($a, $b) {
            return $b['volume'] - $a['volume'];
        });  

        /*BTC and ETH price and price change*/
        $change=$this->curl('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=BTC,ETH');
        $change=json_decode($change);

        $btc_price=round($change->data->BTC->quote->USD->price,2);
        $btc_price_change=round($change->data->BTC->quote->USD->percent_change_24h,2);
        $eth_price=round($change->data->ETH->quote->USD->price,2);
        $eth_price_change=round($change->data->ETH->quote->USD->percent_change_24h,2);

          //bitcoin eth dominance
        $dom=$this->curl('https://pro-api.coinmarketcap.com/v1/global-metrics/quotes/latest?convert=USD');
         //dd($dom);      
        $data=json_decode($dom);
            $totalmcap=$data->data->quote->USD->total_market_cap;
            $totalcapusd=Auth::user()->price_convert($totalmcap);
            $total24hr=Auth::user()->price_convert($data->data->quote->USD->total_volume_24h);
            
            $dominance['btc']=$data->data->btc_dominance/100;
            $dominance['eth']=$data->data->eth_dominance/100;
            $dominance['alt']=(100-($data->data->btc_dominance+$data->data->eth_dominance))/100;

            //dd($dominance); 
        $widgets=Auth::user()->widget_settings;  
        $all_widgets=Widget::All();  
       //dd($widgets);
        return view('user.user_dashboard')->with('topcoins',$topcoins)->with('totalcapusd',$totalcapusd)->with('total24hr',$total24hr)->with('volume',$volume)->with('feeds',$new_feeds)->with('fear',$fear)->with('dominance',$dominance)->with('btc_price',$btc_price)->with('btc_price_change',$btc_price_change)->with('eth_price',$eth_price)->with('eth_price_change',$eth_price_change)->with('widgets',$widgets)->with('all_widgets',$all_widgets);

    }
    public function profile() /*Cryptocomare API*/
    {

        //$r=$this->curl('https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?start=1&limit=5000&convert=USD');
        //$url = 'https://sandbox-api.coinmarketcap.com/v1/global-metrics/quotes/historical?count=1&interval=monthly';
        //$r=$this->curl($url);
        //dd('fg');
       
        $feeds=Feed::All();

        /*Enabled feed list*/
        foreach ($feeds as $key => $feed) {
            if($feed->isFeedEnabled())
            {
                $feed_array[]=$feed->id;
               
            }
            if(!empty($feed_array))
            {
                $new_feeds=FeedData::whereIn('feed_id',$feed_array)->take(20)->orderBy('pub_timestamp','DESC')->get();
            }
            else
            {
                $new_feeds=[];
            }     
        } 

        //market sentiment
        $res= Curl::to('https://api.alternative.me/fng/')->withContentType('application/json')->get();
             $data=json_decode($res);
             //dd($data);
             $name=$data->name;
             $fear['fear']=$data->data['0']->value;
            $fear['class']=$data->data['0']->value_classification;
        //Top 10 Coin Alert
       

        $topcoins = $this->curl2('https://min-api.cryptocompare.com/data/top/mktcapfull?limit=10&tsym=USD');
        $topcoins = json_decode($topcoins);
        $topcoins2=$topcoins->Data; 
        //dd($topcoins);
        /*
        CoinInfo->Name
        DISPLAY->USD->MKTCAP
        DISPLAY->USD->PRICE
        DISPLAY->USD->CHANGEPCTHOUR
        DISPLAY->USD->CHANGEPCT24HOUR*/
       // dd($volchange);

        
        /*$totalcapusd = Curl::to('https://api.coinmarketcap.com/v1/global/?convert=BTC')->withContentType('application/json')->get();
        $totalcapusd    = json_decode( $totalcapusd);    */             
        //dd($totalcapusd);
        $volume = Curl::to('https://api.binance.com/api/v1/ticker/24hr')->withContentType('application/json')->get();
        $volume = json_decode($volume , TRUE); 
        usort($volume, function($a, $b) {
            return $b['volume'] - $a['volume'];
        });  

        /*BTC and ETH price and price change*/
        $change=$this->curl2('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC,ETH&tsyms=USD');
        $change=json_decode($change);
       // dd($change);
        $btc_price=$change->DISPLAY->BTC->USD->PRICE;
        $btc_price_change=$change->DISPLAY->BTC->USD->CHANGEPCTHOUR;
        $eth_price=$change->DISPLAY->ETH->USD->PRICE;
        $eth_price_change=$change->DISPLAY->ETH->USD->CHANGEPCTHOUR;

          //bitcoin eth dominance
        $dom=$this->curl('https://pro-api.coinmarketcap.com/v1/global-metrics/quotes/latest?convert=USD');
         //dd($dom);      
        $data=json_decode($dom);
            $totalmcap=174272191244.901;
            $totalcapusd=Auth::user()->price_convert($totalmcap);
            $total24hr=Auth::user()->price_convert(108201549878.489);
            
            $dominance['btc']=65.52/100;
            $dominance['eth']=8.25/100;
            $dominance['alt']=(100-(65.52+8.25))/100;

            //dd($dominance); 
        foreach ($topcoins2 as $key => $value) {
            $value->RAW->USD->VOLUME24HOURTO=Auth::user()->price_convert( $value->RAW->USD->VOLUME24HOURTO);
            $value->RAW->USD->MKTCAP=Auth::user()->price_convert($value->RAW->USD->MKTCAP);
            $value->DISPLAY->USD->CHANGEPCT24HOUR=round($value->DISPLAY->USD->CHANGEPCT24HOUR,2);
        }    
        $widgets=Auth::user()->widget_settings;  
        $all_widgets=Widget::All();  
       //dd($widgets);
        $alerts=AlertLog::orderby('id','DESC')->get();
        return view('user.user_dashboard')->with('topcoins2',$topcoins2)->with('totalcapusd',$totalcapusd)->with('total24hr',$total24hr)->with('volume',$volume)->with('feeds',$new_feeds)->with('fear',$fear)->with('dominance',$dominance)->with('btc_price',$btc_price)->with('btc_price_change',$btc_price_change)->with('eth_price',$eth_price)->with('eth_price_change',$eth_price_change)->with('widgets',$widgets)->with('all_widgets',$all_widgets)->with('alerts',$alerts);


    }
     public function SignalSettings()
    {
        $signals=Signal::all();
        $settings=Subscription::where('user_id',Auth::id())->get();
        $timeframes=TimeFrame::all();
        return view('user.signals_settings')->with('signal',$signals)->with('time',$timeframes)->with('settings',$settings);
    }
    public function OnSignalSettings(Request $request)
    {
      
         $data=explode(",", $request->data);
         $signalid=$data[0];
         $time_frameid=$data[1];
        
         $savesettings=new Subscription;
         $savesettings->user_id=Auth::id();
         $savesettings->time_frame=$time_frameid;
         $savesettings->signal_id=$signalid;
         $savesettings->save();

    }
    public function OffSignalSettings(Request $request)
    {
      
         $data=explode(",", $request->data);
         $signalid=$data[0];
         $time_frameid=$data[1];
        
         $settings=Subscription::where('user_id',Auth::id())->get();

         foreach ($settings as $key => $value) {
             if($value->time_frame == $time_frameid && $value->signal_id == $signalid)
             {
               
                $value->delete();
             }
         }
        

    }
    public function SaveTheme(Request $request)
    {
        $user=Auth::user();
        $theme=$user->theme;
        if($theme==0)
        {
            $user->theme=1;
        }
        else
        {
            $user->theme=0;
        }
        $user->save();
    }
    public function SaveWidgets(Request $request)
    {
        foreach ($request->positions as $key => $value) {
            //dd($key);
            $widget=UserWidgetSetting::where('widget_id',$key)->where('user_id',Auth::user()->id)->first();
            if(!empty($widget))
            {

                $widget->height=$value['height'];
                $widget->width=$value['width'];
                $widget->x=$value['x'];
                $widget->y=$value['y'];
                $widget->save();
            }
        }
        //dd($request->top_coin);
    }
    public function settings()
    {
        return view('user.user_settings');
    }
    public function PostUserSettings(Request $request)
    {
       if($request->google2fa )
       {
            // Initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');
            // $google2fa = app(Google2FA::class);

            // Save the registration data in an array
            $registration_data = $request->all();

            // Add the secret key to the registration data
            $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();
            // Save the registration data to the user session for just the next request
            $request->session()->put('registration_data', $registration_data);
            //dd(session('registration_data'));

            // Generate the QR image. This is the image the user will scan with their app
            // to set up two factor authentication

            $QR_Image = $google2fa->getQRCodeUrl(
                config('app.name'),
                Auth::user()->email,
                $registration_data['google2fa_secret']
            );  

            $qrCode = new \Endroid\QrCode\QrCode($QR_Image);
            $qrCode->setSize(100);
            $google2fa_url = $qrCode->writeDataUri();

            // Pass the QR barcode image to our view        
            return view('google2fa.enable', ['QR_Image' => $google2fa_url, 'secret' => $registration_data['google2fa_secret']]);
       }
       else
       {
            $user=User::find(Auth::user()->id);
            $user->google2fa_secret=NULL;
            $user->authentication_status=0;
            $user->save();
            return Redirect::route('settings')->with('success','Google 2FA authentication disabled');
       }

    }
    public function enable2fa($secret)
    {
        $user=User::find(Auth::user()->id);
        $user->google2fa_secret=$secret;
        $user->authentication_status=1;
        $user->save();
        return Redirect::route('settings')->with('success','Google 2FA authentication enabled');
    }
    public function AjaxliveDashboard(Request $request)
    {
        /*BTC and ETH price and price change*/
        $change=$this->curl2('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC,ETH&tsyms=USD');
        $change=json_decode($change);
       // dd($change);
        $btc_price=$change->DISPLAY->BTC->USD->PRICE;
        $btc_price_change=$change->DISPLAY->BTC->USD->CHANGEPCTHOUR;
        $eth_price=$change->DISPLAY->ETH->USD->PRICE;
        $eth_price_change=$change->DISPLAY->ETH->USD->CHANGEPCTHOUR;

        $topcoins = $this->curl2('https://min-api.cryptocompare.com/data/top/mktcapfull?limit=10&tsym=USD');
        $topcoins = json_decode($topcoins);
        $topcoins=$topcoins->Data; 
        //dd($topcoins);
        
        $totalmcap=174272191244.901;
        $totalcapusd=Auth::user()->price_convert($totalmcap);
        $total24hr=Auth::user()->price_convert(108201549878.489);
        
        $dominance['btc']=65.52/100;
        $dominance['eth']=8.25/100;
        $dominance['alt']=(100-(65.52+8.25))/100;



        $live['totalcapusd']  =  $totalcapusd;
        $live['total24hr']  =  $total24hr;

        $live['btc']['price']=$btc_price;
        $live['btc']['price_change']=$btc_price_change;
        if($btc_price_change > 0) 
        { 
            $live['btc']['icon']=' <i class="fas fa-caret-up"></i>'; 
            $live['btc']['color']= '#58ca6a';
        }
        else 
        { 
            $live['btc']['icon']='<i class="fas fa-caret-down"></i>';
            $live['btc']['color']= '#f04a38';
        }

        $live['eth']['price']=$eth_price;
        $live['eth']['price_change']=$eth_price_change;
        if($eth_price_change > 0) 
        { 
            $live['eth']['icon']=' <i class="fas fa-caret-up"></i>'; 
            $live['eth']['color']= '#58ca6a';
        }
        else 
        { 
            $live['eth']['icon']='<i class="fas fa-caret-down"></i>';
            $live['eth']['color']= '#f04a38';
        }
        foreach ($topcoins as $key => $value) {
            $value->RAW->USD->VOLUME24HOURTO=Auth::user()->price_convert( $value->RAW->USD->VOLUME24HOURTO);
            $value->RAW->USD->MKTCAP=Auth::user()->price_convert($value->RAW->USD->MKTCAP);
            $value->DISPLAY->USD->CHANGEPCT24HOUR=round($value->DISPLAY->USD->CHANGEPCT24HOUR,2);
        }

        

       
        //dd($topcoins);
        $topcoins['topcoins']=$topcoins;
        return json_encode(['header'=>$live,'topcoins'=>$topcoins]); 
    }
    public function AjaxliveDashboard2(Request $request)
    {
        /*BTC and ETH price and price change*/
        $change=$this->curl('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=BTC,ETH');
        $change=json_decode($change);

        $btc_price=round($change->data->BTC->quote->USD->price,2);
        $btc_price_change=round($change->data->BTC->quote->USD->percent_change_24h,2);
        $eth_price=round($change->data->ETH->quote->USD->price,2);
        $eth_price_change=round($change->data->ETH->quote->USD->percent_change_24h,2);

        $topcoins = $this->curl('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?limit=10&convert=USD');
        $topcoins = json_decode($topcoins);
        $topcoins=$topcoins->data;

        $dom=$this->curl('https://pro-api.coinmarketcap.com/v1/global-metrics/quotes/latest?convert=USD');
         //dd($dom);      
        $data=json_decode($dom);
            $totalmcap=$data->data->quote->USD->total_market_cap;
          
            $totalcapusd=Auth::user()->price_convert($totalmcap);

            $total24hr=Auth::user()->price_convert($data->data->quote->USD->total_volume_24h);


        $live['totalcapusd']  =  $totalcapusd;
        $live['total24hr']  =  $total24hr;

        $live['btc']['price']=$btc_price;
        $live['btc']['price_change']=$btc_price_change;
        if($btc_price_change > 0) 
        { 
            $live['btc']['icon']=' <i class="fas fa-caret-up"></i>'; 
            $live['btc']['color']= '#58ca6a';
        }
        else 
        { 
            $live['btc']['icon']='<i class="fas fa-caret-down"></i>';
            $live['btc']['color']= '#f04a38';
        }

        $live['eth']['price']=$eth_price;
        $live['eth']['price_change']=$eth_price_change;
        if($eth_price_change > 0) 
        { 
            $live['eth']['icon']=' <i class="fas fa-caret-up"></i>'; 
            $live['eth']['color']= '#58ca6a';
        }
        else 
        { 
            $live['eth']['icon']='<i class="fas fa-caret-down"></i>';
            $live['eth']['color']= '#f04a38';
        }
        foreach ($topcoins as $key => $value) {
            $value->quote->USD->volume_24h=Auth::user()->price_convert( $value->quote->USD->volume_24h);
            $value->quote->USD->market_cap=Auth::user()->price_convert($value->quote->USD->market_cap);
        }

        /*bt and eth dominace*/
         $dom=$this->curl('https://pro-api.coinmarketcap.com/v1/global-metrics/quotes/latest?convert=USD');
         //dd($dom);      
        $data=json_decode($dom);
            $totalmcap=$data->data->quote->USD->total_market_cap;
            $totalcapusd=Auth::user()->price_convert($totalmcap);
            $total24hr=Auth::user()->price_convert($data->data->quote->USD->total_volume_24h);
            
            $live['btc_dominance']=$data->data->btc_dominance/100;
            $live['eth_dominance']=$data->data->eth_dominance/100;
            $live['alt_dominace']=(100-($data->data->btc_dominance+$data->data->eth_dominance))/100;

       
        //dd($topcoins);
        $topcoins['topcoins']=$topcoins;
        return json_encode(['header'=>$live,'topcoins'=>$topcoins]); 
    }
    public function addRemoveWidgets(Request $request)
    {
        if(!empty($request->widget_status))
        {

            $remove=UserWidgetSetting::where('user_id',Auth::user()->id)->whereNotIn('widget_id',$request->widget_status)->get();
            foreach ($remove as $key => $each) {
                $each->delete();
            }
           
            foreach ($request->widget_status as $key => $value) {
                $exist=UserWidgetSetting::where('user_id',Auth::user()->id)->where('widget_id',$value)->first();
                if(empty($exist)) /*widget is new one*/
                {
                     $count=UserWidgetSetting::where('user_id',Auth::user()->id)->count();
                    if($count > 0)
                    {

                        $maxy=UserWidgetSetting::where('user_id',Auth::user()->id)->max('y');
                        $maxx=UserWidgetSetting::where('user_id',Auth::user()->id)->max('x');
                        $last=UserWidgetSetting::where('user_id',Auth::user()->id)->where('y',$maxy)->orderby('x','DESC')->first();
                        //dd($last);
                        $next_x=$last->x + $last->width;
                        if($last->x+$last->width > 8)
                        {
                            $next_y=$last->y+$last->height;
                            $next_x=0;
                            //dd('1');
                        } 
                        else
                        {
                              $next_y=$last->y;
                               //dd('2');
                        }
                    }
                    else
                    {
                        $next_x=0;
                        $next_y=0;
                    }

                    $set=new UserWidgetSetting;
                    $set->user_id=Auth::user()->id;
                    $set->widget_id=$value;
                    $set->height=3;
                    $set->width=4;
                    $set->x=$next_x;
                    $set->y=$next_y ;
                    $set->save();
                }
               
                   
                
            }
        }
        else
        {
            $remove=UserWidgetSetting::where('user_id',Auth::user()->id)->get();
            foreach ($remove as $key => $each) {
                $each->delete();
            }
        }
       
       return Redirect::back();
    }
    public function UserPaymentDetails()
    {
        $payments=PaymentDetails::where('user_id',Auth::user()->id)->get();
         return view('user.payment_details')->with('payments',$payments);
    }
    public function GetRsiSubscription()
    {
        $subscription=Subscription::where(['user_id'=>Auth::user()->id])->pluck('time_frame')->toArray();
        $time_periods=TimeFrame::all();
        return view('user.rsi_subscription')->with('user',Auth::user())->with('subscribe',$subscription)->with('time_periods',$time_periods);
    }
    public function PostRsiSubscription(Request $request)
    {
        $s=Subscription::whereNotIn('time_frame',$request->time_frame)->where('user_id',Auth::user()->id)->delete();
           
        foreach ($request->time_frame as $key => $time) {
            if(Subscription::where(['time_frame'=>$time,'user_id'=>Auth::user()->id])->exists())
            {
                $subscribe= Subscription::where(['time_frame'=>$time,'user_id'=>Auth::user()->id])->first();
            }
            else
            {
                $subscribe=new Subscription;
            }
            $subscribe->user_id=Auth::user()->id;
            $subscribe->period=Auth::user()->subscription_period;
            $subscribe->time_frame=$time;
            $subscribe->save();
        }
        return Redirect::back()->with('success', 'Your Subscription added.');
    }
    public function GetSmaSubscription()
    {
        $subscription=Subscription::where(['user_id'=>Auth::user()->id])->pluck('time_frame')->toArray();
        $time_periods=TimeFrame::all();
        return view('user.sma_subscription')->with('user',Auth::user())->with('subscribe',$subscription)->with('time_periods',$time_periods);
    }
    public function PostSmaSubscription()
    {

    }
     public function SaveUserLink(Request $request)
    {
          $validator = Validator::make($request->all(), [
        'link' => 'required|url'
        ]);
          $exist=UserLinks::where(['user_id'=>Auth::user()->id,'link'=>$request->link])->exists();
          if ($validator->fails()) 
          {
                return json_encode(['result'=>"The :Feedlink field Must be a Valid URL."]);
          } 
          else 
          {
            if($exist)
            {
                return json_encode(['message'=>"Record Saved"]); 

            }
            else
            {
              
                $userlink=new UserLinks;
                $userlink->user_id=Auth::user()->id;
                $userlink->link=$request->link;
                if($userlink->save())
                {
                   return json_encode(['message'=>"Record Saved"]); 
                }
            }  
          }
    }

    public function Feed()
    {

        
        
           /* $onemonth=strtotime('now -4 week');
            $oldfeed=FeedData::where('pub_timestamp','<',$onemonth)->get();
            foreach ($oldfeed as $key => $delvalue) {
               $delvalue->delete();
            }

            $feed_list=Feed::where('category',"7")->get();
            $all_feeds=Feed::orderBy('id','DESC')->get();*/
            /*foreach ($all_feeds as $key => $feed) 
            {*/
                /*$ch = curl_init($feed->feed_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
                
                $data = curl_exec($ch);

                curl_close($ch);    
                
                $invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
                $data = preg_replace($invalid_characters, '', $data);
                
                $data=simplexml_load_string($data,null,LIBXML_NOCDATA);

                $data=$this->objectsIntoArray($data);
                //dd($data);
                if($feed->category=='7')  
                   {

                       
                        //dd($data);
                        if(isset($data['entry']))
                        {
                            foreach($data['entry'] as $key=> $item)
                            {
                                if($key=="2")
                
                                if (isset($item['title'])) 
                                {

                                   
                                    if(!FeedData::where('title',$item['title'])->exists())
                                    {
                                       
                                        $feed_data=new FeedData;
                                        $feed_data->feed_id = $feed->id; 
                                        //dd($item['title']);       
                                        $feed_data->title = $item['title'];
                                        $link=$item['link']['@attributes'];
                                        $feed_data->link = $link['href'];
                                        
                                        
                                       
                                        $feed_data->pub_date = $item['published'];
                                        $feed_data->pub_timestamp=strtotime($item['published']);
                                        
                                        $category=["youtube",$item['author']['name']];
                                        $item_description=$item['title'].$item['author']['name'];
                                        //dd($item_description);
                                        if(!empty($category))
                                        {
                                            $feed_data->category = serialize( $category);
                                           //dd($feed_data->category) ;
                                        }
                                        else
                                        {
                                            $c[]=$item['title'];
                                            $feed_data->category = serialize($c);
                                        }
                                        if(!empty($item_description)) 
                                        {
                                            $feed_data->description = serialize($item_description);
                                        }
                                       
                                      

                                        if($feed_data->pub_timestamp > $onemonth)
                                        {
                                           

                                            $feed_data->save();
                                        }

                                    }
                                }
                            }  
                         
                    
                                       
                         }  
                                    


                    
                }*/
          /*  }*/
                /*
                dd($data['channel']);
                if(isset($data['channel']['item']))
                {

                    foreach($data['channel']['item'] as $item)
                    {
                        if(!FeedData::where('title',$item['title'])->exists())
                        {
                           
                            $feed_data=new FeedData;
                            $feed_data->feed_id = $feed->id; 
                            //dd($item['title']);       
                            $feed_data->title = $item['title'];
                            $feed_data->link = $item['link'];
                            $feed_data->pub_date = $item['pubDate'];
                            $feed_data->pub_timestamp=strtotime($item['pubDate']);
                            if(!empty($item['category']))
                            {
                                $feed_data->category = serialize( $item['category']);
                            }
                            else
                            {
                                $c[]=$data['channel']['title'];
                                $feed_data->category = serialize($c);
                            }
                            if(!empty($item['description'])) 
                            {
                                $feed_data->description = serialize($item['description']);
                            }
                            if($feed_data->pub_timestamp > $onemonth)
                            {

                                $feed_data->save();
                            }

                        }
                    }    
                }
            }                     
        */
            $subscription_type=Auth::user()->subscription_type;
            $subscription_category=$this->getCategory($subscription_type);
            $subscription_details=UserCategory::find($subscription_type);//take subsription details for a specifc type user
        
            $enabled_feedcategory=explode(",",$subscription_details->feed_category);
            if(Auth::user()->login_status==0)
            {
               $feeds=Feed::wherein('category',$enabled_feedcategory)->get();
               foreach ($feeds as $key => $value) 
               {
                 $user_feed=new UserFeed;
                 $user_feed->feed_id=$value->id;
                 $user_feed->user_id=Auth::user()->id;
                 $user_feed->save();

                 $userdata= User::find(Auth::user()->id);
                 $userdata->login_status=1;
                 $userdata->save();
               }
            }
        
           
        ini_set('memory_limit','500M');
        $user_feed=UserFeed::where('user_id',Auth::user()->id)->pluck('feed_id')->toArray(); 
        /*list of enabled feeds by user*/
        $added_feeds=Feed::whereIn('id',$user_feed)->orderBy('id','DESC')->get(); /*take enabled feeds from feeds*/
        $enabled_feeds=Feed::whereIn('id',$user_feed)->wherein('category',$enabled_feedcategory)->orderBy('id','DESC')->pluck('id')->toArray(); 
        
        //$unenabledcategories=FeedsCategory::wherenotin('id',$enabled_feedcategory)->get();
        
      $categories=FeedsCategory::wherein('id',$enabled_feedcategory)->get();
       // $categories=FeedsCategory::all();
       
        $feed_count=FeedData::max('id');
        if($enabled_feeds)
        {
            $all_feeds=FeedData::whereIn('feed_id',$enabled_feeds)->orderBy('pub_timestamp','DESC')->take(40)->get();
           $c=count($all_feeds);
            if($c!=null)
            {
                  
                $feed_provider=Feed::All();
                
            
                return view('user.user_feeds')->with(['feeds'=>$all_feeds,'feed_list'=>$added_feeds,'user_category'=>$subscription_category,'all_feeds'=> $feed_provider,'categories'=>$categories,'feed_count'=>$feed_count]);
              
            }
            else
            {

                $feed_provider=Feed::All();
                return view('user.user_feeds')->with(['message'=>"No Feed Data Available..",'all_feeds'=> $feed_provider,'categories'=>$categories,'feed_count'=>$feed_count]);   
             
             }

        }
        else
        {
              
             $feed_provider=Feed::All();
             return view('user.user_feeds')->with(['message2'=>"No fields are enabled to show...!",'all_feeds'=>$feed_provider,'categories'=>$categories,'feed_count'=>$feed_count]);

        }
        
    }
    public function AddUserFeed($id)
    {
        $exist=UserFeed::where('feed_id',$id)->first();
        if($exist)
        {
            $exist->delete();
            return Redirect::back()->with('success','Feed Successfully Removed from the list');
        }
        else
        {
            $user_feed=new UserFeed;
            $user_feed->feed_id=$id;
            $user_feed->user_id=Auth::user()->id;
            $user_feed->save();
            return Redirect::back()->with('success','Feed Successfully Added to the list');
        }
    }
    public function AddUserFeedAjax(Request $request)
    {
        $id=$request->id;
        $exist=UserFeed::where('feed_id',$id)->where('user_id',Auth::user()->id)->first();
        if($exist) /* subscribed*/
        {
            $exist->delete();
            $user_feed=UserFeed::where('user_id',Auth::user()->id)->pluck('feed_id')->toArray();
            $user_feeds=Feed::whereIn('id',$user_feed)->orderBy('id','DESC')->get();

            return json_encode($user_feeds);
        }
       
        else
        {
            $user_feed=new UserFeed;
            $user_feed->feed_id=$id;
            $user_feed->user_id=Auth::user()->id;
            $user_feed->save();
            $user_feed=UserFeed::where('user_id',Auth::user()->id)->pluck('feed_id')->toArray();
            $user_feeds=Feed::whereIn('id',$user_feed)->orderBy('name')->get();
            return json_encode($user_feeds);
        }
    }
    public function FeedSettingModal(Request $request)
    {   
        $feed_details=[];
        $categories=FeedsCategory::All();
        //dd($categories);
        return json_encode(['categories'=>$categories]); 
    }
    
     public function SaveFeedSetting(Request $request)
    {   
        ini_set('memory_limit','500M');
        $values=$request->values;
        $category=$request->category;
        $language=$request->language;

        if(empty( $values) )
        {

            $exist=UserFeed::where('user_id',Auth::user()->id)->get(); 
            if($exist!=null)
            {
               

                   foreach ($exist as $key => $value) 
                   {
                        if(isset($value->feedsData))
                        {
                    //dd($value->feedsData);
                            $each_feed=$value->feedsData;
                            if($each_feed->category==$category  && ($each_feed->language==$language ||  $each_feed->language==0))
                            {

                                $value->delete();
                            }
                        }
                   }
               
            }
        }
        else
        {

            $exist=UserFeed::where('user_id',Auth::user()->id)->get(); 
            if($exist!=null)
            {
                   foreach ($exist as $key => $value) 
                   {
                        if(isset($value->feedsData))
                        {
                            $each_feed=$value->feedsData;
                            
                            if($each_feed->language=='0')
                            {

                                if($each_feed->category==$category && $each_feed->language==$language)
                                {

                                    $enabled[]=$value->feed_id;
                                }
                            }
                            else
                            {
                                if($each_feed->category==$category)
                                {

                                    $enabled[]=$value->feed_id;
                                }
                            }
                        }
                   }
                  
                   if(!empty($enabled))
                   {

                       $countenabled=count($enabled);
                       $countvalues=count($values);
                       if($countenabled>$countvalues)
                       {
                         
                          $diff= array_diff($enabled,$values);
                           foreach ($diff as $key => $value) 
                           {

                               $feed=UserFeed::where('feed_id',$value)->first();
                                 
                                if($feed->feedsData->category==$category && ($feed->feedsData->language==$language || $language=='0'))
                                {

                                    $feed->delete();
                                }    
                           }
                           foreach ($values as $key => $value) 
                           {
                             $feed=Feed::find($value);
                            if($feed->category==$category && $feed->language==$language)
                            {
                               $exist=UserFeed::where(['feed_id'=>$feed->id,'user_id'=>Auth::user()->id])->exists() ;
                               if($exist==null)
                               {

                                   $user_feed=new UserFeed;
                                   $user_feed->feed_id=$value;
                                   $user_feed->user_id=Auth::user()->id;
                                   $user_feed->save();
                               }
                            }   
                           }
                       }
                       if($countenabled<$countvalues)
                       {
                           $diff=array_diff($values,$enabled);
                           $del=array_diff($enabled,$values);
                           foreach ($del as $key => $value) {

                               $feed=UserFeed::where('feed_id',$value)->first();
                               $feed->delete();
                           }
                           foreach ($diff as $key => $value) 
                           {

                               $user_feed=new UserFeed;
                               $user_feed->feed_id=$value;
                               $user_feed->user_id=Auth::user()->id;
                               $user_feed->save();
                           }
                       }
                       if($countenabled==$countvalues)
                       {
                           $diff=array_diff($enabled,$values);
                           
                           if($diff!=null)
                           {
                               $add=array_diff($values,$enabled);
                               $del=array_diff($enabled,$values);
                              
                               foreach ($del as $key => $value) 
                               {
                                    $feed=UserFeed::where('feed_id',$value)->first();
                                    if($feed->feedsData->category==$category && $feed->feedsData->language==$language)
                                    {
                                        $feed->delete();
                                    }
                               }
                              
                               foreach ($add as $key => $value) 
                               {

                                   $feed=UserFeed::where('feed_id',$value)->first();
                                   if($feed->feedsData->category==$category && $feed->feedsData->language!=$language)
                                   {

                                      $user_feed=new UserFeed;
                                      $user_feed->feed_id=$value;
                                      $user_feed->user_id=Auth::user()->id;
                                      $user_feed->save();
                                   }
                               }
                               
                           }
                       }
                   }
                   else
                   {
                        foreach ($values as $key => $value) 
                        {
                               $user_feed=new UserFeed;
                               $user_feed->feed_id=$value;
                               $user_feed->user_id=Auth::user()->id;
                               $user_feed->save();
                                                          
                        }
                   }
            }
            
        }

         return json_encode(['success'=>'Changes Successfully Applied']);
       
    }
     public function FeedSettings()
    {
        $feeds=Feed::All();
        return view('user.user_feed_setting')->with('feeds',$feeds);
    }


    public function FeedTagsAjax(Request $request)
    {
        ini_set('memory_limit','500M');
        $feed_tags=FeedData::uniqueTags();
        //dd($feed_tags);
        return json_encode($feed_tags);
    }
    public function FeedAjax(Request $request)
    {
        
        $tag=$request->tag;
        $type=$request->type;
        $time=$request->time;
        $oldfeedcount=$request->feed_count;
        /*dd($tag.'-'.$type.'-'.$time);*/
        $feed_set=[];
       
        $type_set=[];
        $tag_set=[];
        $time_set=[];
        $feed_details=[];
        $newfeedcount=FeedData::max('id');
        if($newfeedcount>$oldfeedcount)
        {

             ini_set('memory_limit','500M');
            $new_feeds=FeedData::where('id','>',$oldfeedcount)->get();

            foreach ($new_feeds as $key => $each) 
            {
                if($each->feed->isFeedEnabled())
                {
                    $feed_array[]=$each->id;    
                }    
            }
            
            //$new_feeds=FeedData::whereIn('id',$feed_set)->orderBy('pub_timestamp','ASC')->get();
            if(isset($type)  && isset($feed_array)) /*type filter*/
            {
                
                     
                $all_feeds=FeedData::whereIn('id',$feed_array)->orderBy('pub_timestamp','ASC')->get();
                foreach ($all_feeds as $key => $feed) 
                {
                    if(in_array($feed->feed->category, $type))
                    {
                        $type_set[]=$feed->id;
                    }
                    
                }
               
            }
           
             
           
            if(isset($tag)  && isset($feed_array)) /*tag filter*/
            {
                $inputs=explode(',', $tag);
               
                $all_feeds=FeedData::whereIn('feed_id',$feed_array)->orderBy('pub_timestamp','ASC')->get();
                foreach ($all_feeds as $key => $feed) {

                    $tag_group=unserialize($feed->category);
                    $title=strtolower($feed->title);
                    $source=$feed->feed->name;
                    foreach ($inputs as $key => $each_tag) 
                                    {

                                           
                                      if(strpos($title, strtolower($each_tag) )!== false)
                                        {
                                          // $tag_test[]= strpos($title, strtolower($tag) );
                                            $tag_set[]=$feed->id;


                                        }
                                        if(strpos(strtolower($source),strtolower($each_tag))!== false)
                                        {
                                            $tag_set[]=$feed->id;
                                            
                                        }

                                        if(is_array($tag_group))
                                        {
                                            $tag_group= array_map('strtolower',$tag_group);
                                            if( in_array(strtolower($each_tag), $tag_group) )
                                            {
                                               $tag_set[]=$feed->id;
                                            }
                                        }
                                        else
                                        {
                                           if($each_tag===$tag_group) 
                                           {
                                                $tag_set[]=$feed->id;
                                           }
                                        }
                                    }
                }
            }
           /* if(isset($time) && isset($feed_array)) 
            {

                
                $all_feeds=FeedData::whereIn('id',$feed_array)->orderBy('pub_timestamp','ASC')->get();

                $timestamp=$this->timeClass($time);
                        
                $time_set=FeedData::where('pub_timestamp','>',$timestamp)->orderBy('pub_timestamp','DESC')->pluck('id')->All();
                
            }*/
            $time_set=[];
            if(!empty($type_set) && !empty($type) ) {  $arrays[]=$type_set; }
            
          
            if(!empty($tag_set) && !empty($tag)){ /*dd($tag_set);*/ $arrays[]=$tag_set; }
            //dd($time!=='0');
            if($time!=='0') {$arrays[]=$time_set;}
                $c=[];

            if(empty($arrays))
            {
                if(!empty($feed_array))
                {

                    if($time==='0' && $tag==null && $type==null)
                    {
                     
                        $array=$feed_array;
                        $new_feeds=FeedData::whereIn('id',$array)->orderBy('pub_timestamp','ASC')->get();
                    }
                    else
                    {
                      return json_encode(['result'=>'0']); 
                    }
                }
                else
                {
                    $new_feeds=[];
                }

                
            }
            else
            {

                $array=$arrays;
                
                if(count($array)>1)
                {
                    $array=array_intersect(...$array);
                }
                foreach ($array as $key => $value) {
                    if(is_array($value))
                    {
                        foreach($value as $value1)
                        {
                            $c[]=$value1;
                        }
                    }
                    else
                    {
                        $c[]=$value; 
                    }
                }
                $new_feeds=FeedData::whereIn('id',$c)->orderBy('pub_timestamp','ASC')->get();
            }
            //$array = array_filter($arrays);
            

                
                
          
               
            //dd($new_feeds);
            if(!empty($new_feeds))
            {

                foreach ($new_feeds as $key => $each) 
                {
                     $feed_details[$key]['id']=$each->id;
                    $feed_details[$key]['time']=/*$each->time()*/'Few Sec';
                    //$feed_details[$key]['time_class']=$each->timeClass();
                    $feed_details[$key]['tags']=$each->tags();
                    $feed_details[$key]['title']=$each->title;
                    $feed_details[$key]['link']=$each->link;
                    $feed_details[$key]['pub_date']=$each->pubDate();
                    $feed_details[$key]['pub_time']=$each->pubTime();
                    $feed_details[$key]['last_update']=$each->id;
                    $feed_details[$key]['feed_provider']=$each->feed->name;
                    $feed_details[$key]['feed_name']=$each->feed->name;
                   /* $feed_details[$key]['feed_category']=$each->feed->category;*/
                    $feed_details[$key]['sound']=Auth::user()->notification_sound;
                    $feed_details[$key]['icon']=$each->feed->getCategory->icon;
                }
                    return json_encode(['result'=>$feed_details]);
            }
        }
        else
        {
            return json_encode(['result'=>'0']);
        }
    }
   
   public function FeedAjaxFetchDescription(Request $request)
      {
          $id=$request->id;
          $feeddata=FeedData::find($id);
          $source=$feeddata->feed->name;
          $timeStamp=$feeddata->pub_timestamp;
          $datetimeFormat = 'd-M-Y H:i:s';
          $date = new \DateTime();
          // If you must have use time zones
          // $date = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
          $date->setTimestamp($timeStamp);
          $time =  $date->format($datetimeFormat);
          $title=$feeddata->title;
          $description=strip_tags(unserialize($feeddata->description));
          $description=substr($description,0,500);
          //$description=strip_tags( $string); 
          $link=$feeddata->link;
          $data['id']=$id;
          
          $our_link=route('NewsRead',[$id,preg_replace('/\s+/', '_', $title)]);
         
          $data['html']='
            <div class="article-dropdown">
                <div class="article-dropdown__inner">
                <div class="close icon-close close_js"> 
                    <i class="fas fa-times"></i>
                </div>
                <div  class="mCSB_container  "  > 
                    <h2 data-id="'.$id.'">
                        <a href="'.$link.'" class="content-title icon-link" target="_blank">
                        '.$title.'
                        </a> 
                    </h2> 
                    <p> '.$description.'</p>
                    <span class="timer">'.$time.'</span>
                    <div class=" clearfix "></div>
                    <div class="socials-block">
                        <i class="fas fa-link"></i>  '.$source.'
                        </a> 
                    </div>
                    <div class="share-button">
                        <div class="lid">Share Post</div>
                            <div class="share-items-wrapper">
                                <div class="share-items">
                                  <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$our_link.'" class="share-item">      
                                    <i class="fab fa-facebook-f"></i>    
                                  </a>
                                  <a target="_blank" href="https://twitter.com/intent/tweet?text=Cryptoscannerpro&amp;url='.$our_link.'" class="share-item">
                                    <i class="fab fa-twitter"></i>
                                  </a> 
                                  <a target="_blank" href="fb-messenger://share/?link='.$our_link.'&app_id=123456789" class="share-item">
                                   <i class="fab fa-facebook-messenger"></i>
                                  </a>
                                  <a target="_blank"  href="https://telegram.me/share/url?url='.$our_link.'&text=Cryptoscannerpro" class="share-item">
                                    <i class="fab fa-telegram-plane"></i>
                                  </a> 
                                </div>
                            </div>
                            <div class="thank-you">
                              Thank you
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
             ';
          return json_encode($data);
      }
      public function timeClass($time=null) /*generate classes based on time*/
      {
            if($time=='0')
            {
                $timestring='-5 days';
            }
            if($time=='1')
            {
                $timestring='-1 hours';
            }  
            if($time=='24')
            {
                $timestring='-24 hours';
            }  
            if($time=='48')
            {
                $timestring='-48 hours';
            }  
            if($time=='5')
            {
                $timestring='-5 days';
            }  

          $to_time = time();
          $new_time = date("Y-m-d H:i:s", strtotime($timestring, $to_time));
       
          $ts=strtotime($new_time);
          return  $ts;
         
          
      }   
    public function FeedAjaxTest(Request $request)
    {
        ini_set('memory_limit','500M');

        
        $tag=$request->tag;
        $time=$request->time;
        $type=$request->type;

        $load=$request->load;
        $feed_set=[];
        $type_set=[];
        $cat_set=[];
        $time_set=[];
        $tag_set=[];
        $feed_details=[];
        $feeds=Feed::All();
       
        /*Enabled feed list*/
        foreach ($feeds as $key => $feed) 
        {
            if($feed->isFeedEnabled())
            {
                $feed_array[]=$feed->id;
            }
        }
        
        if(empty($feed_array))
        {
            return json_encode(['nodata'=>'Feeds Not Enabled']);
        }
        $all_feeds=FeedData::whereIn('feed_id',$feed_array)->orderBy('pub_timestamp','DESC')->get();


        foreach ($all_feeds as $key => $feed) 
        {
            if(isset($type)) /*type filter*/
            {
                if(in_array($feed->feed->category, $type))
                {
                    $type_set[]=$feed->id;
                }
            }

            if(isset($tag)) /*tag filter*/
            {
                $inputs=explode(',', $tag);
                //$tag_group=unserialize($feed->category);
                //dd($tag_group);
                $tag_decode=json_encode($feed->category);
                $tag_group=json_decode($tag_decode);


                $title=strtolower($feed->title);
                
                $source=$feed->feed->name;
                
                foreach ($inputs as $key => $each_tag) 
                                {

                                       
                                  if(strpos($title, strtolower($each_tag) )!== false)
                                    {
                                      // $tag_test[]= strpos($title, strtolower($tag) );
                                        $tag_set[]=$feed->id;


                                    }
                                    if(strpos(strtolower($source),strtolower($each_tag))!== false)
                                    {
                                        $tag_set[]=$feed->id;
                                        
                                    }

                                    if(is_array($tag_group))
                                    {
                                        $tag_group= array_map('strtolower',$tag_group);
                                        if( in_array(strtolower($each_tag), $tag_group) )
                                        {
                                           $tag_set[]=$feed->id;
                                        }
                                    }
                                    else
                                    {
                                       if($each_tag===$tag_group) 
                                       {
                                            $tag_set[]=$feed->id;
                                       }
                                    }
                                }
            }
           

        }
        if(isset($time)) /*time filter*/
        {
            
            $timestamp=$this->timeClass($time);
                    
            $time_set=FeedData::where('pub_timestamp','>',$timestamp)->orderBy('pub_timestamp','DESC')->pluck('id')->All();
             //dd($time_set);
        }
        
        if(!empty($type_set) && !empty($type) ) {  $arrays[]=$type_set; }
        
        if(!empty($tag_set) && !empty($tag)){  $arrays[]=$tag_set; }
        
        if($time!=='0') $arrays[]=$time_set;

        
        if(empty($arrays) )
        {
            if(empty($type) && empty ($tag) && $time=='0')
            {
                $n=FeedData::whereIn('feed_id',$feed_array)->orderBy('pub_timestamp','DESC')->pluck('id')->toArray();
                if($n==null)
                {
                   
                    $message="No Feeds Are Available..";
                    return json_encode(['message'=>$message]); 
                }
            }
            else
            {

                $message="No Feeds Are Available..";
                    return json_encode(['message'=>$message]); 
            }
           
        }
        else
        {
            if( (empty($type_set) && !empty($type)) || (empty($tag_set) && !empty($tag)) || ($time!=='0' && empty($time_set)) )
            {
                $message="No Feeds Are Available..";
                return json_encode(['message'=>$message]);
            }
                
                $array=$arrays;
                    $c=[];
                    
                    
                if(count($array)>1)
                {
                    $array=array_intersect(...$array);
                }
                foreach ($array as $key => $value) {
                    if(is_array($value))
                    {
                        foreach($value as $value1)
                        {
                            $c[]=$value1;
                        }
                    }
                    else
                    {
                        $c[]=$value; 
                    }

                }
                //dd($c);
                
                 
                $n=FeedData::whereIn('id',$c)->orderBy('pub_timestamp','DESC')->pluck('id')->toArray();
            
            
                 
           
 
           
        }
        $new_feed=FeedData::whereIn('id',$n)->orderBy('pub_timestamp','DESC')->get();
            $n=array_slice($n,0,$load);

            
            $new_feeds=FeedData::whereIn('id',$n)->orderBy('pub_timestamp','DESC')->get();
           //dd($new_feeds);
            if(empty($new_feeds))
            {
                
                $message="No Feeds Are Available..";
                    return json_encode(['message'=>$message]); 
            }
        //$new_feeds=FeedData::whereIn('id',$c)->orderBy('pub_timestamp','ASC')->get();
        
        foreach ($new_feeds as $key => $each) {
            $feed_details[$key]['id']=$each->id;
            $feed_details[$key]['time']=$each->time();
           // //$feed_details[$key]['time_class']=$each->timeClass();
            $feed_details[$key]['tags']=$each->tags();
            $feed_details[$key]['title']=$each->title;
            $feed_details[$key]['link']=$each->link;
            $feed_details[$key]['pub_date']=$each->pubDate();
            $feed_details[$key]['pub_time']=$each->pubTime();
            $feed_details[$key]['last_update']= FeedData::max('id');
            $feed_details[$key]['feed_provider']=$each->feed->name;
            $feed_details[$key]['feed_name']='feed-'.$each->feed->className();
            $feed_details[$key]['feed_category']=' type-'.$each->feed->category;
            $feed_details[$key]['sound']=Auth::user()->notification_sound;
            $feed_details[$key]['icon']=$each->feed->getCategory->icon;
           
        }
        $id=[];
        foreach ($new_feed as $key => $each) {
           $id[]=$each->id;  
        }
        //dd($id);
        return json_encode(['result'=>$feed_details,'id'=>$id]); 
    }
     public function FeedAjaxTestBlank(Request $request)
    {
        ini_set('memory_limit','500M');

        
        $tag=$request->tag;
        $time=$request->time;
        $type=$request->type;

        $load=$request->load;
        $feed_set=[];
        $type_set=[];
        $cat_set=[];
        $time_set=[];
        $tag_set=[];
        $feed_details=[];
        $feeds=Feed::All();

        /*Enabled feed list*/
        foreach ($feeds as $key => $feed) 
        {
            if($feed->isFeedEnabled())
            {
                $feed_array[]=$feed->id;
             
            }
        }
        
        if(empty($feed_array))
        {
            return json_encode(['nodata'=>'Feeds Not Enabled']);
        }
        else
        {
            if(empty($type) && empty ($tag) && $time=='0')
            {

                $n=FeedData::whereIn('feed_id',$feed_array)->orderBy('pub_timestamp','DESC')->pluck('id')->toArray();
                
                if(empty($n))
                {
                    
                    $message="No Feeds Are Available..";
                    return json_encode(['message'=>$message]); 
                }
               
            }
        }

       
        $all_feeds=FeedData::whereIn('feed_id',$feed_array)->orderBy('pub_timestamp','DESC')->get();

        foreach ($all_feeds as $key => $feed) 
        {

            if(isset($type)) /*type filter*/
            {
                if(in_array($feed->feed->category, $type))
                {
                    $type_set[]=$feed->id;
                }
            }
            if(isset($tag)) /*tag filter*/
            {
                $inputs=explode(',', $tag);
                $tag_group=unserialize($feed->category);

                $title=strtolower($feed->title);
                
                $source=$feed->feed->name;
                
                foreach ($inputs as $key => $each_tag) 
                                {

                                       
                                  if(strpos($title, strtolower($each_tag) )!== false)
                                    {
                                      // $tag_test[]= strpos($title, strtolower($tag) );
                                        $tag_set[]=$feed->id;


                                    }
                                    if(strpos(strtolower($source),strtolower($each_tag))!== false)
                                    {
                                        $tag_set[]=$feed->id;
                                        
                                    }

                                    if(is_array($tag_group))
                                    {
                                        $tag_group= array_map('strtolower',$tag_group);
                                        if( in_array(strtolower($each_tag), $tag_group) )
                                        {
                                           $tag_set[]=$feed->id;
                                        }
                                    }
                                    else
                                    {
                                       if($each_tag===$tag_group) 
                                       {
                                            $tag_set[]=$feed->id;
                                       }
                                    }
                                }
            }
            if(isset($time)) /*time filter*/
            {
                
                $timestamp=$this->timeClass($time);
                        
                $time_set=FeedData::where('pub_timestamp','>',$timestamp)->orderBy('pub_timestamp','DESC')->pluck('id')->All();
            }

        }
        if(!empty($type_set) && !empty($type) ) {  $arrays[]=$type_set;  }
         
        
        if(!empty($tag_set) && !empty($tag)){  $arrays[]=$tag_set;   }

       
        if($time!== '0') {$arrays[]=$time_set; }
        
        if(empty($arrays) )
        {
            
            if(empty($type) && empty ($tag) && $time=='0')
            {

                $n=FeedData::whereIn('feed_id',$feed_array)->orderBy('pub_timestamp','DESC')->pluck('id')->toArray();
                //dd($n);
                if(empty($n))
                {
                    $message="No Feeds Are Available..";
                    return json_encode(['message'=>$message]); 
                }
               
            }
            else
            {
                $message="No Feeds Are Available..";
                return json_encode(['message'=>$message]); 
            }
           
        }
        else
        {
            $array=$arrays;
            $c=[];
            if(count($array)>1)
            {
                $array=array_intersect(...$array);
            }
            foreach ($array as $key => $value) {
                if(is_array($value))
                {
                    foreach($value as $value1)
                    {
                        $c[]=$value1;
                    }
                }
                else
                {
                    $c[]=$value; 
                }

            }
            $n=FeedData::whereIn('id',$c)->orderBy('pub_timestamp','DESC')->pluck('id')->toArray();
        }
            $new_feed=FeedData::whereIn('id',$n)->orderBy('pub_timestamp','DESC')->get();
                $n1=array_slice($n,0,$load);
                
                $new_feeds=FeedData::whereIn('id',$n1)->orderBy('pub_timestamp','DESC')->get();
                

            //$new_feeds=FeedData::whereIn('id',$c)->orderBy('pub_timestamp','ASC')->get();
            
            foreach ($new_feeds as $key => $each) {
                $feed_details[$key]['id']=$each->id;
                $feed_details[$key]['time']=$each->time();
                //$feed_details[$key]['time_class']=$each->timeClass();
                $feed_details[$key]['tags']=$each->tags();
                $feed_details[$key]['title']=$each->title;
                $feed_details[$key]['link']=$each->link;
                $feed_details[$key]['pub_date']=$each->pubDate();
                $feed_details[$key]['pub_time']=$each->pubTime();
                $feed_details[$key]['last_update']= FeedData::max('id');
                $feed_details[$key]['feed_provider']=$each->feed->name;
                $feed_details[$key]['feed_name']='feed-'.$each->feed->className();
                $feed_details[$key]['feed_category']=' type-'.$each->feed->category;
                $feed_details[$key]['sound']=Auth::user()->notification_sound;
                $feed_details[$key]['icon']=$each->feed->getCategory->icon;
            }
           
                /* foreach ($new_feed as $key => $each) {
               $id[]=$each->id;  
           
               
            }*/
            return json_encode(['result'=>$feed_details,'id'=>$n]); 
        }
    
    public function PreLoader(Request $request)
    {
        ini_set('memory_limit','500M');
        
        $tag=$request->tag;
        $time=$request->time;
        $type=$request->type;
        $load=$request->load;
        $response[]=$request->response;//recieving ids
       
        $feed_set=[];
        $type_set=[];
        $cat_set=[];
        $time_set=[];
        $tag_set=[];
        $feed_details=[];
        $id=[];

        
        //$feeds=Feed::All();
        foreach ($response as  $value) {
           $id=$value;
        }
        $n=FeedData::whereIn('id',$id)->orderBy('pub_timestamp','DESC')->pluck('id')->toArray();
      
        $n=array_slice($n, 0,40);
      
        $new_feeds=FeedData::whereIn('id',$n)->orderBy('pub_timestamp','DESC')->get();
       
        
        foreach ($new_feeds as $key => $each) {
            $feed_details[$key]['id']=$each->id;
            $feed_details[$key]['time']=$each->time();
            //$feed_details[$key]['time_class']=$each->timeClass();
            $feed_details[$key]['tags']=$each->tags();
            $feed_details[$key]['title']=$each->title;
            $feed_details[$key]['link']=$each->link;
            $feed_details[$key]['pub_date']=$each->pubDate();
            $feed_details[$key]['pub_time']=$each->pubTime();
            $feed_details[$key]['last_update']= FeedData::max('id');
            $feed_details[$key]['feed_provider']=$each->feed->name;
            $feed_details[$key]['feed_name']='feed-'.$each->feed->className();
            $feed_details[$key]['feed_category']=' type-'.$each->feed->category;
            $feed_details[$key]['sound']=Auth::user()->notification_sound;
            $feed_details[$key]['icon']=$each->feed->getCategory->icon;

        }
        return json_encode(['result'=>$feed_details]); 
    }

    public function FeedAjaxFetchLanguage(Request $request)
    {
        $category=$request->id;
        $language=$request->language;
        $user_id=Auth::user()->id;
        $feed=Feed::all();
        $data=[];

        
        foreach ($feed as $key => $single_feed) 
        {
            
            $enabled=$single_feed->isFeedEnabled();

            if($enabled)
            {
               
                if($single_feed->category == $category && ($single_feed->language == $language || $language=='0') )
                {
                    
                    $data[$key]['id']=$single_feed->id;
                    $data[$key]['name']=$single_feed->name;
                    $data[$key]['status']='true';
                   

                }
            }
            else
            {
                if($single_feed->category == $category && ($single_feed->language == $language || $language=='0') )
                {
                    $data[$key]['id']=$single_feed->id;
                    $data[$key]['name']=$single_feed->name;
                    $data[$key]['status']='false';
                   
                }
            }
        }
        dd($data);
        return json_encode(['result'=>$data]); 
        
        


    }

    public function NotificationSoundAjax(Request $request)
    {
        //dd($request->notification);
        if($request->notification==1)
        {
            $user = Auth::user(); 
            $user->notification_sound=1;
            $user->save();
        }
         if($request->notification==0)
        {
            $user = Auth::user(); 
            $user->notification_sound=0;
            //dd($user);
            $user->save();
        }
        return json_encode(['result'=>$user->notification_sound]);
    }
    public function objectsIntoArray($arrObjData, $arrSkipIndices = array())
    {
        $arrData = array();
        
        // if input is object, convert into array
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }
        
        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    } 
    
    public function getCategory($type)
    {
        $subscription_category=UserCategory::find($type);
       
        if($type=="1")
        {
            $data['delay']=$subscription_category->delay;
            $data['signal']=$subscription_category->signal_category;
           
        }
        if($type=="2")
        {
            $data['delay']=$subscription_category->delay;
            $data['signal']=$subscription_category->signal_category;
            //contain additional data

        }
        if($type=="3")
        {
            $data['delay']=$subscription_category->delay;
            $data['signal']=$subscription_category->signal_category;
            //contain additional data
  
        }
        if($type=="4")
        {
            $data['delay']=$subscription_category->delay;
            $data['signal']=$subscription_category->signal_category;
            //contain additional data
        }
      
        return $data;


    }
    public function BillingAddress()
    {
        $address=BillingAddress::where('user_id',Auth::user()->id)->get();
        //dd($address);
        return view('user.billing_address')->with('address',$address);
    }
    public function AddAddress()
    {
        return view('user.add_billing_address');
    }
    public function AddAddressPost(Request $request)
    {
        $address=new BillingAddress;
        $address->name= Auth::user()->name;
        $address->user_id=Auth::user()->id;
        $address->address=$request->address;
        $address->state=$request->state;
        $address->pincode=$request->pincode;
        $address->city=$request->city;
        $address->phone=$request->phone;
        if($address->save())
        {
           return redirect()->back()->with('success',"New Address Created");
        }

    }
    public function EditAddress($id)
    {
        $data=BillingAddress::find($id);
        return view('user.edit_billing_address')->with('edit',$data);
    }
     public function EditAddressPost(Request $request)
    {
        $id=$request->id;
        $address=BillingAddress::find($id);
        $address->name= Auth::user()->name;
        $address->user_id=Auth::user()->id;
        $address->address=$request->address;
        $address->state=$request->state;
        $address->pincode=$request->pincode;
        $address->city=$request->city;
        $address->phone=$request->phone;
        if($address->save())
        {
           return redirect()->back()->with('success',"Updated Successfully");
        }
    }
     public function DeleteAddress($id)
    {
         $delete=BillingAddress::find($id);
      if( $delete->delete())
      {
        return redirect()->back()->with('success'," Successfully Deleted  ");
      }

    }
    public function GetAllFeed(Request $request)
    {
      ini_set('memory_limit','500M');
      
     
      $load=$request->load;
     
      $feeds=Feed::All();

      /*Enabled feed list*/
      foreach ($feeds as $key => $feed) {
          if($feed->isFeedEnabled())
          {
              $feed_array[]=$feed->id;
          }
      } 
      $skip=40;
     
       $new_feeds=FeedData::whereIn('feed_id',$feed_array)->skip($skip)->take(PHP_INT_MAX)->orderBy('pub_timestamp','DESC')->get();
      
          $id=$new_feeds->pluck('id');
          
      
      return json_encode(['id'=>$id]);  
    }
    
        
    
}
