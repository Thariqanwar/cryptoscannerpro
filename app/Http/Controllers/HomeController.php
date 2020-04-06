<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Subscription;
use App\SubscriptionPeriod;
use App\RsiTimeFrame;
use Redirect;
use Hash,Validator,File;
use App\Helpers\AppHelper;
use App\AlertLog;
use App\Feed;
use App\ExportExcel;
use App\FeedData;
use Maatwebsite\Excel\Facades\Excel;
use SimpleXMLElement;
use Symfony\Component\Process\Process;
use App\FeedsCategory;
use App\UserCategory;
use App\UserFeed;
use App\Blog;
use App\Signal;
use App\PaymentDetails;
use App\SubscriptionAmount;
use App\IpLog;
use DOMDocument;
use Twitter;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            $feed_list=Feed::all();
            $all_feeds=Feed::orderBy('id','DESC')->get();
            //$content = file_get_contents('http://rss.leparisien.fr/leparisien/rss/paris-75.xml');
         

        $alert_logs=AlertLog::OrderBy('id','DESC')->get();
        
        $users=User::where('user_type','user')->get();
        return view('admin.dashboard')->with(['users'=>$users,'alert_logs'=>$alert_logs]);
        
    }
    
    public  function downloadExcel()
    {
           
            return Excel::download(new ExportExcel, 'alert_log.xlsx');
            //return (new ExportExcel)->download('invoices.xlsx');
           
    }
    public function SubscriptionAmount()
    {
        $user_category=UserCategory::orderBy('id','DESC')->get();
        $subscription_amount=SubscriptionAmount::OrderBy('id','DESC')->get();
        return view('admin.subscription_amount')->with(['subscription_amount'=>$subscription_amount,'user_category'=>$user_category]);
    }
    public function AddSubscriptionAmount(Request $request)
    {

        $subscription_amount=SubscriptionAmount::OrderBy('id','DESC')->get();
        //dd($subscription_amount);
        foreach ($subscription_amount as $key => $value) {
           $value->amount=$request->price[$key];
           $value->save();
        }

        return Redirect::back()->with('success','Subscription Updated Succefully');
    }
    public function ChangeSubscription(Request $request)
    {
        $id=$request->user_id;
        $user=User::find($id);
        $user->subscription_type=$request->subscription_type;
        $user->subscription_period=$request->subscription_period;
       
        if($user->save())
        {

            return Redirect::back()->with('success','Subscription Updated Succefully');
        }

    }
    
     public function ChangeSubscriptionPeriod(Request $request)
    {
        $id=$request->user_id;
        $user=User::find($id);
        //$user->subscription_type=$request->subscription_type;
        $user->subscription_period=$request->subscription_period;
        $user->password=Hash::make($request->password);
        $user->password_string=$request->password;
        $user->email=$request->email;
        $user->name=$request->name;
        if($user->save())
        {

            return Redirect::back()->with('succes',' Updated Succefully');
        }

    }
    

    public function AddNewUser()
    {

        //AppHelper::screenshot('GTOBTC','15m');
        $sub_periods=SubscriptionPeriod::all();
        
        return view('admin.add_new_user')->with('sub_periods',$sub_periods);
    }
    Public function ViewUser($id)
        {
            $user=User::find($id);
            $subscription_period=SubscriptionPeriod::All();
            $user_types=UserCategory::All();
            $iplog=IpLog::where('user_id',$id)->get();
            $payment_details=PaymentDetails::where('user_id',$id)->get();
                   
            return view('admin.view_user')->with('user',$user)->with('payment_details',$payment_details)->with('iplog',$iplog)->with('user_types',$user_types)->with('subscription_period',$subscription_period) ;
        }

    public function UserFunctionalities()
    {
        $signals = Signal::all();
        $feedCategory = FeedsCategory::all();
        foreach($signals as $signal)
        {
            $data[$signal->category->name][$signal->id] = $signal->name; 
        }
        $freeuser = UserCategory::find(4);
        $freeSelected = ($freeuser->signals) ? explode(',',$freeuser->signals) : '';
        $freefeed = ($freeuser->feed_category) ? explode(',',$freeuser->feed_category):'';

        $free = [
            'selected' => $freeSelected,
            'feed_category' =>$freefeed,
            'delay' => $freeuser->delay,
            'crypto_scanner' => $freeuser->crypto_scanner,
            'smart_trade' => $freeuser->smart_trade,
        ];

        $basicuser = UserCategory::find(3);
        $basicSelected = ($basicuser->signals) ? explode(',',$basicuser->signals) : '';
        $basicfeed = ($basicuser->feed_category) ? explode(',',$basicuser->feed_category) : '';

        $basic = [
            'selected' => $basicSelected,
            'feed_category' =>$basicfeed,
            'delay' => $basicuser->delay,
            'crypto_scanner' => $basicuser->crypto_scanner,
            'smart_trade' => $basicuser->smart_trade,
        ];

        $aduser = UserCategory::find(2);
        $adSelected = ($aduser->signals) ? explode(',',$aduser->signals) : '';
        $adfeed = ($aduser->feed_category) ? explode(',',$aduser->feed_category) : '';

        $ad = [
            'selected' => $adSelected,
            'feed_category' =>$adfeed,
            'delay' => $aduser->delay,
            'crypto_scanner' => $aduser->crypto_scanner,
            'smart_trade' => $aduser->smart_trade,
        ];

        $prouser = UserCategory::find(1);
        $proSelected = ($prouser->signals) ? explode(',',$prouser->signals) : '';
        $profeed = ($prouser->feed_category) ? explode(',',$prouser->feed_category) : '';

        $pro = [
            'selected' => $proSelected,
            'feed_category' =>$profeed,
            'delay' => $prouser->delay,
            'crypto_scanner' => $prouser->crypto_scanner,
            'smart_trade' => $prouser->smart_trade,
        ];
        return view('admin.user_functionality',compact('feedCategory','data','free','basic','ad','pro'));
    }

    public function AddFunctionalities(Request $request)
    {
        // dd($request);
        $free = UserCategory::find(4);
        $free->signals = ($request->freeuser_telegram) ? implode(",",$request->freeuser_telegram) : '';
        $free->feed_category = ($request->freeuser_feed) ? implode(",",$request->freeuser_feed) : '';
        $free->delay = $request->freeuser_delay;
        $free->crypto_scanner = $request->freeuser_scannerpro;
        $free->smart_trade	=$request->freeuser_smart_trade;
        $free->save();

        $basic = UserCategory::find(3);
        $basic->signals = ($request->basic_telegram) ? implode(",",$request->basic_telegram) : '';
        $basic->feed_category = ($request->basic_feed) ? implode(",",$request->basic_feed) :'';
        $basic->delay = $request->basic_delay;
        $basic->crypto_scanner = $request->basic_scannerpro;
        $basic->smart_trade	=$request->basic_smart_trade;
        $basic->save();

        $ad = UserCategory::find(2);
        $ad->signals = ($request->advanced_telegram) ? implode(",",$request->advanced_telegram) : '';
        $ad->feed_category = ($request->advanced_feed) ? implode(",",$request->advanced_feed) : '';
        $ad->delay = $request->advanced_delay;
        $ad->crypto_scanner = $request->advanced_scannerpro;
        $ad->smart_trade	=$request->advanced_smart_trade;
        $ad->save();

        $pro = UserCategory::find(1);
        $pro->signals = ($request->pro_telegram) ? implode(",",$request->pro_telegram) : '';
        $pro->feed_category = ($request->pro_feed) ? implode(",",$request->pro_feed) : '';
        $pro->delay = $request->pro_delay;
        $pro->crypto_scanner = $request->pro_scannerpro;
        $pro->smart_trade	=$request->pro_smart_trade;
        $pro->save();

        return redirect()->route('UserFunctionalities');

    }
    public function PostNewUser(Request $request)
    { 

       $this->validate($request,['name' => 'required','email' => 'required|email|unique:users','password'=>'required','subscription_period'=>'required','password'=>'required']);

       
             $user= new User;
             $user->name=$request->name;
             $user->email=$request->email;
             $user->password_string=$request->password;
             $user->password=Hash::make($request->password);
             $user->subscription_period=$request->subscription_period;
            // $user->telegram_username=$request->username;
             $user->status='0'; /*0 blocked 1 active*/
             $user->user_Type='user';
             $sub_periods=SubscriptionPeriod::find($request->subscription_period);
             if($sub_periods)
             {

                $d=strtotime("+".$sub_periods->text);
             }

             $user->subscription_start = date("Y-m-d h:i:sa");
             $user->subscription_end = date("Y-m-d h:i:sa", $d);
             if($user->save())
             {
                 return Redirect::route('UserList')->with('success','New User Succefully Added');
             }    
       
        
    }
    public function UserIpLog()
    {
        $log=IpLog::all();
        return view('admin.log_list')->with('log',$log);
    }
    public function UserList()
    {
        $users=User::where('user_type','user')->get();
        return view('admin.user_list')->with('users',$users);
    }
    public function UserEdit($id)
    {
        $user=User::find($id);
        $sub_periods=SubscriptionPeriod::all();
        return view('admin.user_edit')->with('user',$user)->with('sub_periods',$sub_periods);
    }
    public function UpdateUser(Request $request,$id)
    {
        $this->validate($request,['name' => 'required','email' => 'required|email|unique:users'. ($id ? ",id,$id" : ''),'password'=>'required','subscription_period'=>'required','password'=>'required']);

        $user=User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password_string=$request->password;
        $user->password=Hash::make($request->password);
        $user->subscription_period=$request->subscription_period;
        //$user->telegram_username=$request->username;
        $user->status=$request->status; /*0 blocked 1 active*/
        $user->user_Type='user';

        $sub_periods=SubscriptionPeriod::find($request->subscription_period);
        if($sub_periods)
        {

           $d=strtotime("+".$sub_periods->text);
        }

        
        $user->subscription_start = date("Y-m-d h:i:sa");
        $user->subscription_end = date("Y-m-d h:i:sa", $d);
        if($user->save())
            {
                 return Redirect::route('UserList')->with('user',$user)->with('success',' User Succefully Updated');
            }
    }
    public function DeleteUser($id)
    {
        $user=User::find($id);
        
        $iplog=IpLog::where('user_id',$id)->get();
        foreach ($iplog as $key => $value) {
            $value->delete();
        }
        

        
        if($user->delete())
        {   
            $users=User::where('user_type','user')->get();
            return Redirect::route('UserList')->with('users',$users)->with('success',' User Succefully Deleted');
        }
    }
    public function GeneratePassword(Request $request)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 15; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return implode($pass); //turn the array into a string
    }
    public function GetTimeFrames(Request $request)
    {
        $subscription=Subscription::where('period',$request->subscription_period)->where('user_id',$request->user)->get();
        return $subscription;
    }
    public function AlertLog()
    {
        $alerts=AlertLog::orderBy('id','DESC')->get();
        return view('admin.alert_log')->with('alerts',$alerts);
    }
    public function IpLog()
    {
        $log=IpLog::all();
        return view('admin.log_list')->with('log',$log);
    }
    public function PaymentDetails()
    {
        $payments=PaymentDetails::All();
        return view('admin.payment_details')->with('payments',$payments);
    }
    public function AlertLogTable()
    {
        $alerts=AlertLog::orderBy('id','DESC')->get();
        return view('admin.alert_log_table')->with('alerts',$alerts);
    }
     public function FeedsList()
    {
    	$feeds_list=Feed::all();
    }
     public function Feed()
    {
        /*$feed_list=Feed::all();
    	$all_feeds=Feed::orderBy('id','DESC')->get();
        //$content = file_get_contents('http://rss.leparisien.fr/leparisien/rss/paris-75.xml');
        foreach ($all_feeds as $key => $feed) {
        
         		$ch = curl_init($feed->feed_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);
       
       			$invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
                $data = preg_replace($invalid_characters, '', $data);
                $data=simplexml_load_string($data,null,LIBXML_NOCDATA);
       			$data=$this->objectsIntoArray($data);
       			//dd($d['channel']);
       			$data['channel']['category']=$feed->name;
       			//dd($data['channel']);
       			//$data->channel->category=$feed->name;
                $feeds[]=$data['channel'];
        }     
     	dd($feeds);*/
        $feed_list=Feed::all();
        $feeds=FeedData::All();
        foreach ($feeds as $key => $value) {
            if(!$value->feed)
            {
                $value->delete();
            }
        }
        return view('admin.feed')->with('feeds',$feeds)->with('feed_list',$feed_list);
    }
    public function AddFeed()
    {
    	
        $feeds= Feed::All();
        $categories=FeedsCategory::orderby('id','ASC')->get();
       /* $list_response=Twitter::getUserTimeline([ 'screen_name' => 'BarackObama', 'count' =>30, 'format' => 'json']);
        $data=json_decode($list_response);
        //dd($list_response);
        try
        {
        	$response = Twitter::getUserTimeline(['screen_name' => 'Bitcoin','count' => 25, 'format' => 'json']);
        }
        catch (Exception $e)
        {
        	 dd(Twitter::error());
        	dd(Twitter::logs());
        }

        dd(Twitter::error());*/
        return view('admin.add_feed')->with('feeds',$feeds)->with('categories',$categories);
    }
     public function PostFeed(Request $request)
    {
        $messages = [
            'feed_url.url' => 'The :attribute field Must be a Valid URL.',
        ];
        if($request->category=='9')
        {
            $this->validate($request,['name' => 'required','feed_url' => 'required','language'=>'required'],$messages);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://twitter.com/".$request->feed_url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $headers = curl_exec($ch);
            curl_close($ch);  
           
            $headers=explode('\r\n',  json_encode($headers));
           
            if (filter_var($request->feed_url, FILTER_VALIDATE_URL)) { 
              return Redirect::back()->with('error','Enter valid Twitter account username')->withInput();
            }
            
             if(strpos($headers[0], '404') !== false ) 
             {
                
                return Redirect::back()->with('error','Twitter account with given username is not exist.')->withInput();
                
             } 

        }
        else
        {

            $this->validate($request,['name' => 'required','feed_url' => 'required|url','language'=>'required'],$messages);
        }
        $ch = curl_init($request->feed_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
          if($request->category=="6" && $request->feed_url=="http://www.cryptoscannerpro.com/admin/blog/RssFeed")
        {

             $path=public_path()."/uploads/blog/feed.xml";
                $data=file_get_contents($path);
           
             
             
        }
    
        $invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
        $data = preg_replace($invalid_characters, '', $data);
        $data=simplexml_load_string($data,null,LIBXML_NOCDATA);
        $data=$this->objectsIntoArray($data);
        //dd($data);
        //$data['channel']['category'];            
        $feed=new Feed;
        $feed->name     =  $request->name;
        $feed->feed_url =  $request->feed_url;
        //$feed->source   =  $request->source;
        $feed->language =  $request->language;
        $feed->category =  $request->category;
        if( $feed->category=="1")
        {

             $feed->channel_name= $data['channel']['title'];
        }
        if($feed->category=="7")
        {
            
           if(isset($data['entry']))
           {
               foreach($data['entry'] as $item)
                            {
                                //dd($item['title']);
                                         $feed->channel_name= $item['title'];  
                                         
                             }
            
           }
           else
           {
                 $feed->channel_name= $data['channel']['title'];
               
           }
             
             
        }
       if($feed->category=="9")
        {

           
             $feed->channel_name= $request->name;
             
        }
        if($feed->category=="6")
        {
            if(isset($data['title']))
            {
              $feed->channel_name= $data['title'];  
            }
            else
            {
               $feed->channel_name= $data['channel']['title'];  
            }   
         
             
               
             
        }
        
        if($feed->save())
        {

            return Redirect::back()->with('success','New RSS feed Succefully Added');
        }
    }
     public function EditFeed($id)
    {
        $feed= Feed::find($id);
        $feeds= Feed::All();
         $categories=FeedsCategory::All();

        return view('admin.admin_edit_feed')->with('edit_feed',$feed)->with('feeds',$feeds)->with('categories',$categories);
    }
     public function UpdateFeed(Request $request)
    {
        $messages = [
            'feed_url.url' => 'The :attribute field Must be a Valid URL.',
        ];
        $this->validate($request,['name' => 'required','feed_url' => 'required|url','language'=>'required'],$messages);
        
        $feed= Feed::find($request->edit_id);
        $feed->name     =  $request->name;
        $feed->feed_url =  $request->feed_url;
        //$feed->source   =  $request->source;
        $feed->language =  $request->language;
        $feed->category =  $request->category;
        if($feed->save())
        {
            return Redirect::back()->with('success','New RSS feed Succefully Updated');
        }
    }
    public function DeleteFeed($id)
    {
    	$feed=Feed::find($id);
        $userfeeds=UserFeed::where('feed_id',$id)->get();
        if($userfeeds)
        {
            foreach($userfeeds as $userfeed)
            {
                $userfeed->delete();
            }
        }
        if($feed->feeds)
        {
            foreach($feed->feeds as $feeddata)
            {
                $feeddata->delete();
            }
        } 
        if($feed->delete())
        {
            return Redirect::back()->with('success','Feed successfully deleted');
        }
    	
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

    public function ListBlog()
    {
        $blog=Blog::All();
        return view('admin.blog_list')->with('blogs',$blog);
    }
    public function AddBlog()
    {
        return view('admin.blog_new');
    }
    public function AddBlogPost(Request $request)
    {
        $blog=new Blog;
        $blog->title=$request->title;
        $blog->content=$request->content;
        $tagarray=explode(',', $request->tags);
        foreach ($tagarray as $key => $tag) {
             $tags[]=$tag;
        }
       
        $blog->tags=serialize($tags);

        $file = $request->file('image');
        $fileName= $file->getClientOriginalName();
        if(Blog::image_exist($fileName))
        {
            $fileName= time().'-'.$fileName;
        }
        $destinationPath = 'public/uploads/blog';
        $file->move($destinationPath,$fileName);
        $blog->image= $fileName;

        if($blog->save())
        {
           app('App\Http\Controllers\ChartController')->AddBlogFeedUrl();
            return Redirect::back()->with('success','Feed successfully Added');
        }      
    }
    public function updateBlog($id)
    {
        $blog=Blog::find($id);
        return view('admin.blog_edit')->with('blog',$blog);
    }
    public function updateBlogPost(Request $request)
    {
        $id=$request->id;
        $blog= Blog::find($id);
        $blog->title=$request->title;
        $blog->content=$request->content;
        $tagarray=explode(',', $request->tags);
        foreach ($tagarray as $key => $tag) {
             $tags[]=$tag;
        }
        
        $blog->tags=serialize($tags);
        if($request->file('image'))
        {
            File::delete('public/uploads/blog/'.$blog->image);
            $file = $request->file('image');
            $fileName= $file->getClientOriginalName();
            if(Blog::image_exist($fileName))
            {
                $fileName= time().'-'.$fileName;
            }
            $destinationPath = 'public/uploads/blog';
            $file->move($destinationPath,$fileName);
            $blog->image= $fileName;
        }

        if($blog->save())
        {
            app('App\Http\Controllers\ChartController')->AddBlogFeedUrl();
            return Redirect::back()->with('success','Feed successfully Updated');
        }  
    }
    public function BlogDelete(Request $request)
    {
        $delete=Blog::find($request->id);
        File::delete('public/uploads/blog/'.$delete->image);
        if($delete->delete())
        {
            return json_encode(['result'=>1]);
        }
        else
        {
            return json_encode(['result'=>0]);
        }
    }
    
}
