<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\LowPrice;
use App\Helpers\AppHelper;
use Hash;
use DOMDocument;
use App\Blog;
use App\FeedData;

class ChartController extends Controller
{
    public function Front()
    {
         //market sentiment

      /*$topcoins = Curl::to('https://api.coinmarketcap.com/v1/ticker/?limit=10&sort=rank')->withContentType('application/json')->get();               
      $topcoins = json_decode($topcoins); 
      foreach ($topcoins as $key => $top) {
        $coins[]=$top->symbol.'USDT';
      }
     // dd($topcoins);
      $result=[];
      $timeframe=['1h','1d','1w'];
      foreach ($coins as $key1 => $coin) {
       
        foreach ($timeframe as $key2 => $time) {
          
          $url="https://api.binance.com/api/v1/klines?symbol=".$coin."&interval=".$time."&limit=2";
          $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);
          $data=json_decode($data);
          $now=$data[0][4];
          $past=$data[1][4];
          $diff=$now-$past;
          $result[$key1]['coin']=$coin;
          if($time=='1h')
          {
            $result[$key1]['onehr']=round($diff/$past*100,2);
          }
          elseif($time=='1d')
          {
            $result[$key1]['oneday']=round($diff/$past*100,2); 

          }
          elseif ($time=='1w') {
           $result[$key1]['oneweek']=round($diff/$past*100,2);
          }
          $now=$data[0][5];
          $past=$data[1][5];
          $diff=$now-$past;
          $result[$key1]['vol']=round($diff/$past*100,2);
          $result[$key1]['price']=round($data[0][4],2);
           $result[$key1]['volume24']=round($data[0][5],2);
          
        }
      }*/
      //dd( $result);
     /* $ch = curl_init('https://www.reddit.com/user/alienth/.rss');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $data = curl_exec($ch);
      curl_close($ch);
      if(substr($data, 0,5)=='<?xml' )
      {
          $invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
          $data = preg_replace($invalid_characters, '', $data);
          $data=simplexml_load_string($data,null,LIBXML_NOCDATA);
          $data=$this->objectsIntoArray($data);
          //dd($data);
          $set=[];
          if(isset($data['entry']))
                              {
                                  foreach($data['entry'] as $item)
                                  {


                                      if (isset($item['title'])) 
                                      {
                                        if(!FeedData::where('title',$item['title'])->exists())
                                        {
                                            
                                           
                                            $feed_data=new FeedData;
                                            $feed_data->feed_id = 1; 
                                            //dd($item['title']);       
                                            $feed_data->title = $item['title'];
                                            $link=$item['link']['@attributes'];
                                            $feed_data->link = $link['href'];
                                            
                                            
                                           
                                            $feed_data->pub_date = $item['updated'];
                                            $feed_data->pub_timestamp=strtotime($item['updated']);
                                            
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
                                            
                                            

                                                //$feed_data->save();
                                              $set[]=$feed_data;
                                            
                                        }
                                      }
                                  } 
                                  dd($set); 
                              } 
      }*/
      return view('front');
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
     public function Faq()
    {
      $parameters = [
        'Max length'=>'2000'
      ];

        $res= Curl::to('https://coincodex.com/apps/coincodex/cache/all_coins.json')->withContentType('application/json')->get();
             $data=json_decode($res);
            //dd($res);
           $market_cap=0;

            foreach ($data as $key => $value) 
            {
              if($value->symbol=="BTC")
              {
                $btc_marketcap=$value->market_cap_usd;
              }
              if($value->symbol=="ETH")
              {
                $eth_marketcap=$value->market_cap_usd;
              }
               $market_cap+=$value->market_cap_usd;
              }
              $sumcap=$btc_marketcap+$eth_marketcap;
              $alt_marketcap=$market_cap-$sumcap;
              
           
            $btc_dominance=$btc_marketcap/$market_cap;
            $eth_dominance=$eth_marketcap/$market_cap;
            $alt_dominance=$alt_marketcap/$market_cap;
           
           
            /*dd("btc ",$btc_marketcap,"eth", $eth_marketcap,"alt",$alt_marketcap,"tot",$market_cap,"total",$total);*/
             /* $parameters = [
                'limit'=>'10','tsym'=>'USD'
              ];*/
     /* $url = 'https://min-api.cryptocompare.com/data/blockchain/list';
      $headers = [
        
        'authorization: Apikey fdb6939c2b0580f48e897c76590ecc1c8f8dd777af25201bc756da35d659b026'
      ];
      $qs = http_build_query($parameters); // query string encode the parameters
      $request = "{$url}?{$qs}"; // create the request URL


      $curl = curl_init(); // Get cURL resource
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => $request,            // set the request URL
        CURLOPT_HTTPHEADER => $headers,     // set the headers 
        CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
      ));

      $response = curl_exec($curl); // Send the request, save the response
      $data=json_decode($response); // print json decoded response
     //$symbols=$data['data'];
      //dd($response);
      // Close request
      $symbol=$data->Data;
      $symbolname="";
      $i=0;
     foreach ($symbol as $key => $value) {
      $i++;
       if($i<900)
       {

      $symbolname.=",".$value->symbol;
         }


     }
     //dd($symbolname);
     
      
      $para = [
                'fsyms'=>$symbolname,'tsyms'=>'USD'
              ];
     $url = 'https://min-api.cryptocompare.com/data/pricemultifull';
     $headers = [
       
       'authorization: Apikey fdb6939c2b0580f48e897c76590ecc1c8f8dd777af25201bc756da35d659b026'
     ];
     $qs = http_build_query($para); // query string encode the parameters
     $request = "{$url}?{$qs}"; // create the request URL


     $curl = curl_init(); // Get cURL resource
     
     curl_setopt_array($curl, array(
       CURLOPT_URL => $request,            // set the request URL
       CURLOPT_HTTPHEADER => $headers,     // set the headers 
       CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
     ));

     $response = curl_exec($curl); // Send the request, save the response
     $data=json_decode($response);





     dd($response);
     curl_close($data); 
     */
      return view('faq');
    }
     public function ReferFriend()
    {
      /*dd(Hash::make('admin666@csp$'));
      dd(Hash::make('ars225@crypto#'));*/
      return view('refer_friend');
    }
      public function GetSupport()
    {
      /*dd(Hash::make('admin666@csp$'));
      dd(Hash::make('ars225@crypto#'));*/
      return view('get_support');
    }
    public function cookies_policy()
    {
      return view('cookies_policy');
    }
    public function disclaimer()
    {
      return view('disclaimer');
    }
    public function terms_of_use()
    {
      return view('terms_of_use');
    }
    public function referal_agreement()
    {
      return view('referal_agreement');
    }
    public function privacy_policy()
    {
      return view('privacy_policy');
    }
    public function newsRead($id,$title)
    {
      $feed=FeedData::find($id);
       return view('news_read')->with('feed',$feed);
    }
    public function ShowChart($symbol,$interval)
    {
    	return view('index')->with('symbol',$symbol)->with('interval',$interval);
    }
    public function hrDivergence($symbol,$interval)
    {
      return view('hr_divergance')->with('symbol',$symbol)->with('interval',$interval);
    }

///////////////////////
    public function ShowChartOneHour($symbol,$interval)
    {
    	return view('oneHourindex')->with('symbol',$symbol)->with('interval',$interval);
    }
/////////////////////////////////////
      public function AddBlogFeedUrl()//blog rss feed creation 
     {
            $blog=Blog::All();
           
            $rowCount = count($blog);
           
            $xmlDoc = new DOMDocument("1.0");

           /*  $xmlDoc->loadHTML($string);*/
             $rssfeed = '<rss version="2.0">';
            /*  $root = $xmlDoc->appendChild($xmlDoc->createElement("rss"));*/

             $root = $xmlDoc->appendChild($xmlDoc->createElement("channel"));
            /* $root->appendChild($xmlDoc->createElement("channel"));*/
             $root->appendChild($xmlDoc->createElement("title","blogs"));
             $root->appendChild($xmlDoc->createElement("link","http://www.cryptoscannerpro.com"));
             //$tabUsers = $root->appendChild($xmlDoc->createElement('item'));

             foreach($blog as $key=> $user)
             {
                 
                 if(!empty($user))
                 {
                     $tabUser =  $root->appendChild($xmlDoc->createElement('item'));
                    
                        
                         $tabUser->appendChild($xmlDoc->createElement('title', $user->title));
                         $tabUser->appendChild($xmlDoc->createElement('link',"http://www.cryptoscannerpro.com/". $user->id));
                         $tabUser->appendChild($xmlDoc->createElement('category',$user->tags));
                         $tabUser->appendChild($xmlDoc->createElement('description',"our blog"));
                         $tabUser->appendChild($xmlDoc->createElement('pubDate',$user->created_at));
                 }
             }
             header("Content-Type: text/xml");
            
             $xmlDoc->formatOutput = true;
             
               
             $file_name ='feed.xml';
             $xmlDoc->save("public/uploads/blog/" .$file_name);
            // $e=readfile("public/uploads/blog/" .$file_name);
             $path=public_path()."/uploads/blog/feed.xml";
                 $data=file_get_contents($path);
            
           
                 return response($data)
         ->withHeaders([
             'Content-Type' => 'text/xml'
         ]);
    }
     

    public function TestChart()
    {
      return view('test');
    }
    public function ViewBlog($id)
    {

      $blog=Blog::where('id',$id)->get();
     // dd($blog);
      return view('admin.viewblog')->with('viewBlog',$blog);
    }
    public function CandleSticks(Request $request)
    {
    	$symbol=$request->symbol;
    	$interval=$request->interval;
    	$startTime=1561420800;
    	$endTime=1561507200;
    	$limit=50;
    	
      $saved_result=LowPrice::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
    	$response=$saved_result->result; /*file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);*/
    	$response=json_decode($response);
    	$data=[];
    	foreach ($response as $key => $value) 
    	{
    		
    		$data[$key][0]=floatval($value[6]); 
    		$data[$key][1]=floatval($value[1]);
    		$data[$key][2]=floatval($value[2]);
    		$data[$key][3]=floatval($value[3]);
    		$data[$key][4]=floatval($value[4]);		
    	}
      //array_pop($data);
    	$points=LowPrice::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();

        return array($data,$points);     	    		    	
    }
    //RSI Calculation        
    public static function calculateRSI($datas){             
            $j = $k = 1; $count = ($datas)?count($datas):0;
            $period = 0.07142857; // 1/period  1/14    (RSI period)
            
            $close = $timestamp = $change = $gain = $loss = $avg_gain = $avg_loss = $rs = $rsi = array();     
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
                            $rsi[0] = 100 - (100/(1+$rs[0])); //rsi value
                            $timestamp[$k]=$data[6]; //timestamp
                            $close[$k]=$data[4]; //close value
                        }else{
                            $rsi[0] = 100; //rsi value
                            $timestamp[$k]=$data[6]; //timestamp
                            $close[$k]=$data[4]; //close value
                        }
                    } 
                    if($key > 13){

                        $avg_gain[$k] = $gain[$key]*$period+(1-$period)*$avg_gain[$k-1];
                        $avg_loss[$k] = $loss[$key]*$period+(1-$period)*$avg_loss[$k-1];                    
                        if($avg_loss[$k] > 0){
                            $rs[$k] = $avg_gain[$k]/$avg_loss[$k];
                            $rsi[$k] = 100 - (100/(1+$rs[$k])); //rsi value
                            $timestamp[$k]=$data[6]; //timestamp
                            $close[$k]=$data[4]; //close value
                        }else{
                            $rsi[$k][0] = 100; //rsi value
                            $timestamp[$k]=$data[6]; //timestamp
                            $close[$k]=$data[4]; //close value
                        }
                        $k++;
                    }
                    if($j == ($count-1))
                        break;
                    $j++; 
                }
            }
            //dd($timestamp);
            return array($timestamp,$close,$rsi);
        }
    
    public function screenshot($symbol)
    {
       
        //$image=$_SERVER['DOCUMENT_ROOT'].'/public/telegram/Image.jpg';
        $image='/var/www/html/cryptonew/public/telegram/Image.jpg';
        //print_r($image);
         $url = 'http://cryptoscannerpro.com/'.$symbol;
        //$image = 'Images.jpeg';
        $accountKey = 'c13a8b';
        $ch = curl_init('http://api.screenshotmachine.com/?key=c13a8b&dimension=1024x786&delay=4000&cacheLimit=0&url='.$url);
        //print_r($ch);
        //$ch = curl_init('http://api.screenshotmachine.com/?key='.$accountKey.'&size=M&format=png&url='.$url);
        $fp = fopen($image, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $chat_id    = '-1001496915788';
        $bot_url    = "https://api.telegram.org/bot750434260:AAHuRAw0m3H9jbomD0riylrD2o9NpKBDJ-8/";
        $urls        = $bot_url . "sendPhoto?chat_id=" . $chat_id;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); 
        $post_fields = array(
            'photo'     => new \CURLFile(realpath($image))
            
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $urls); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
        $output = curl_exec($ch);
        print_r($output);
    }
    public function find_low_high($low_high)
    {
        $response=json_decode($low_high);
        $data=[];
        foreach ($response as $key => $value) 
        {
            $data[$key]=floatval($value[4]);     
        }
        return max($data); 
    }
    public function find_low_price(){

            
      //$coins = file_get_contents('https://api.binance.com/api/v1/ticker/24hr',false, $context);
      $curl_handle=curl_init();
      curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/ticker/24hr');
      curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
      curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
      $coins = curl_exec($curl_handle);
      curl_close($curl_handle);
      //print_r($coins);
      $coin_list = json_decode($coins);
      $coins=[];
      /*foreach ($coin_list as $key => $coin) 
      {
        if('BTC'==substr($coin->symbol, -3))
        {
          if('LRCBTC'!=$coin->symbol){
            $coins[]=$coin->symbol;
          }
          
        }
      }
      $coins[]='BTCUSDT'; $coins[]='ETHUSDT'; */
      //$coins=['BTCUSDT','ETHUSDT','BNBUSDT','EOSUSDT'];
      $coins=['XLMBTC'];
      $interval_list=['15m','1h','4h','6h','12h','1d'];
      foreach ($interval_list as $key => $interval) 
      {
        foreach ($coins as $key => $symbol) 
        {
         // print_r($symbol."  ".$interval."   ");

        
          $limit=50;
          //$api_response = file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit,false, $context);
          $curl_handle=curl_init();
          curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);
          curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
          $api_response = curl_exec($curl_handle);
          curl_close($curl_handle);
          //sleep(1);
          $json_response = json_decode($api_response);
          
          $full_rsi=AppHelper::calculateRSI($json_response);

          unset($second_point);
          if(array_key_exists(35, $full_rsi['close']) && array_key_exists(35, $full_rsi['time']) && array_key_exists(35, $full_rsi['rsi']))
          {
            if( min($full_rsi['close'])==$full_rsi['close'][35] )
            {
              $min_price_1_rsi = $full_rsi['rsi'][35];
              if($min_price_1_rsi <=40) 
              {
                $second_point=['time'=>$full_rsi['time'][35],'close'=>$full_rsi['close'][35],'rsi'=>$full_rsi['rsi'][35]];
                $second_point_key=35; 
              }
            } 
          }
          else
          {
            continue ;
          }

          if((isset($second_point)) && (!isset($first_point)))
          {   
            for ($i=34; $i > 0 ; $i--) 
            { 
              if(($full_rsi['close'][$i] > $second_point['close']) && ($full_rsi['rsi'][$i] < $second_point['rsi']) && ($full_rsi['rsi'][$i] <= 20) )
              {
                $first_point=['time'=>$full_rsi['time'][$i],'close'=>$full_rsi['close'][$i],'rsi'=>$full_rsi['rsi'][$i]];

                $first_point_key=$i;
                $diff=$second_point_key - $first_point_key; /*Number of candle sticks between to points*/
                $rsi_set=array_slice($full_rsi['rsi'], $first_point_key,$diff);
                if(count(array_slice($full_rsi['close'], $first_point_key,$diff)) >=7)
                { 
                  $previous=LowPrice::where([ ['symbol','=',$symbol],['time_interval','=',$interval] ])->first();
                  if(!empty($previous))
                  {
                    $first=strtotime($previous->created_at);
                    $now= strtotime(date('Y-m-d H:m:s'));
                    $tinterval  = abs( $first-$now);
                    $minutes   = round($tinterval / 60);
                    if($previous->time_2 <= $first_point['time'] )
                    {
                      
                      if(min($rsi_set) >= $first_point['rsi']) /*if rsi less than first point rsi it will not take screenshot*/
                      {
                          
                        $rsi_down=0; 

                        for ($i=$first_point_key+1; $i < $second_point_key ; $i++) 
                        { 
                          $ax=$full_rsi['time'][$first_point_key];
                          $ay=$full_rsi['rsi'][$first_point_key];
                          $bx=$full_rsi['time'][$second_point_key];
                          $by=$full_rsi['rsi'][$second_point_key];
                          $cx=$full_rsi['time'][$i];
                          $cy=$full_rsi['rsi'][$i];/*32.448970985985*/;
                          $r=(($bx - $ax)*($cy - $ay) - ($by - $ay)*($cx - $ax));  
                          if($r < 0)
                          {
                            $rsi_down=1; /*rsi goes down to line*/
                          }
                        }

                        $price_down=0; 
                        for ($i=$first_point_key+1; $i < $second_point_key ; $i++) 
                        { 
                          $ax=$full_rsi['time'][$first_point_key];
                          $ay=$full_rsi['close'][$first_point_key];
                          $bx=$full_rsi['time'][$second_point_key];
                          $by=$full_rsi['close'][$second_point_key];
                          $cx=$full_rsi['time'][$i];
                          $cy=$full_rsi['close'][$i];/*32.448970985985*/;
                          $r=(($bx - $ax)*($cy - $ay) - ($by - $ay)*($cx - $ax));  
                          if($r < 0)
                          {
                            $price_down=1; /*rsi goes down to line*/
                          }
                        }
                        if(($rsi_down==0) && ($price_down==0))
                        {
                          $per_difference=$second_point['close']-$first_point['close'];
                          $per=$per_difference/$second_point['close'] * 100;
                          if($per >= 2)
                          {
                            $previous->symbol =$symbol;
                            $previous->time_interval =$interval;

                            $previous->low_price_ =$first_point['close'];
                            $previous->low_rsi_ =$first_point['rsi'];
                            $previous->time_1 =$first_point['time'];

                            $previous->low_price_ = $second_point['close'];
                            $previous->low_rsi_ = $second_point['rsi'];
                            $previous->time_2 = $second_point['time'];
                            $previous->result = $api_response;
                            $previous->created_at = date("Y-m-d H:m:s");
                            if($previous->save())
                            {
                              //$txt=" #New Final Condition Met for" .$interval." <b>#".$symbol."</b>";
                              //$status=AppHelper::sendMessage($txt);
                              $screenshot= AppHelper::screenshot($symbol,$interval);
                              break 2;  /*break the loop and go to next coin*/
                            } 
                          }
                        }
                      }
                    }    
                  }
                  else
                  { 
                    if(min($rsi_set) >= $first_point['rsi']) /*if rsi less than first point rsi it will not take screenshot*/
                    {
                      $rsi_down=0; 

                      for ($i=$first_point_key+1; $i < $second_point_key ; $i++)  /*loop to check rsi go down*/
                      {  
                        $ax=$full_rsi['time'][$first_point_key];
                        $ay=$full_rsi['rsi'][$first_point_key];
                        $bx=$full_rsi['time'][$second_point_key];
                        $by=$full_rsi['rsi'][$second_point_key];
                        $cx=$full_rsi['time'][$i];
                        $cy=$full_rsi['rsi'][$i];/*32.448970985985*/;
                        $r=(($bx - $ax)*($cy - $ay) - ($by - $ay)*($cx - $ax));  
                        if($r < 0)
                        {
                          $rsi_down=1; /*rsi goes down to line*/
                        }
                      }
                      $price_down=0; 
                      for ($i=$first_point_key+1; $i < $second_point_key ; $i++) /*loop to check price go down*/
                      {
                        $ax=$full_rsi['time'][$first_point_key];
                        $ay=$full_rsi['close'][$first_point_key];
                        $bx=$full_rsi['time'][$second_point_key];
                        $by=$full_rsi['close'][$second_point_key];
                        $cx=$full_rsi['time'][$i];
                        $cy=$full_rsi['close'][$i];/*32.448970985985*/;
                        $r=(($bx - $ax)*($cy - $ay) - ($by - $ay)*($cx - $ax));  
                        if($r < 0)
                        {
                          $price_down=1; /*rsi goes down to line*/
                        }
                      }
                      if(($rsi_down==0) && ($price_down==0)) /*if lines not breach rsi and price */
                      {
                        $per_difference=$second_point['close']-$first_point['close'];
                        $per=$per_difference/$second_point['close'] * 100;
                        if($per >= 2)
                        {
                          $set = new LowPrice;
                          $set->symbol=$symbol;
                          $set->time_interval=$interval;

                          $set->low_price_1=$first_point['close'];
                          $set->low_rsi_1=$first_point['rsi'];
                          $set->time_1=$first_point['time'];

                          $set->low_price_2=$second_point['close'];
                          $set->low_rsi_2=$second_point['rsi'];
                          $set->time_2=$second_point['time'];
                          $set->result=$api_response;
                          $set->created_at=date("Y-m-d H:m:s");
                          if($set->save())
                          {
                           //$txt=" #New Final Condition Met for" .$interval." <b>#".$symbol."</b>";
                           //$status=AppHelper::sendMessage($txt);
                            $screenshot= AppHelper::screenshot($symbol,$interval);
                            break 2;  /*break the loop and go to next coin*/
                          } 
                        }  
                      }  
                    }  
                  }die;
                }
                else
                {
                  unset($first_point);
                  continue;
                }  
              }
            }   
          }
        }//print_r($status); //foreach close  
      }  
    } //function close
    public function Testing()
    {
         $curl_handle=curl_init();
            curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/ticker/24hr');
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
            $coins = curl_exec($curl_handle);
            curl_close($curl_handle);
            $coin_list = json_decode($coins);
      $coins=[];
      foreach ($coin_list as $key => $coin) 
      {
        if('BTC'==substr($coin->symbol, -3))
        {
          $coins[]=$coin->symbol;
        }
      }
      $coins[]='BTCUSDT'; $coins[]='ETHUSDT'; 
      //$coins=['BTCUSDT','ETHUSDT','BNBUSDT','EOSUSDT'];
      //$interval_list=['15m','1h','4h','6h','12h','1d'];
      $interval_list=['1h','4h','6h','12h','1d'];
      foreach ($interval_list as $key => $interval) 
      {
        foreach ($coins as $key => $symbol) 
        {
           $limit=50;
          //$api_response = file_get_contents('https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit,false, $context);
          $curl_handle=curl_init();
          curl_setopt($curl_handle, CURLOPT_URL,'https://api.binance.com/api/v1/klines?symbol='.$symbol.'&interval='.$interval.'&limit='.$limit);
          curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
          $api_response = curl_exec($curl_handle);
          curl_close($curl_handle);
          //sleep(1);
          $json_response = json_decode($api_response);
           $full_rsi=$this->calculateRSI2($json_response);
          dd($json_response);
        }  
      }    
    }
     public static function calculateRSI2($datas)
    {             
      $j = 1; $k = 1; $count = ($datas)?count($datas):0;
      $period = 0.07142857; // 1/period  1/14    (RSI period)
      $close = $timestamp = $change = $gain = $loss = $avg_gain = $avg_loss = $rs = $rsi = array();     
      if($datas)
      { 
        foreach($datas as $key => $data)
        {                             
          if(count($datas)< 15)
            break;
          $change[$key] = $datas[$j][4] - $data[4];      
          $gain[$key] = ($change[$key] > 0 )? $change[$key] : 0;
          $loss[$key] = ($change[$key] < 0 )? abs($change[$key]) : 0;     
          if($key == 13)
          {
            $avg_gain[0] = (array_sum($gain)/14);
            $avg_loss[0] = (array_sum($loss)/14);
            if($avg_loss[0] > 0)
            {
              $rs[0] = $avg_gain[0]/$avg_loss[0];
            }
            else
            {
              $rs[0] = 0;
            }
            if($avg_loss[0] > 0)
            {
              $rsi[0] = 100 - (100/(1+$rs[0])); //rsi value
            }
            else
            {
              $rsi[0] = 100; //rsi value
            }
          } 
          if($key > 13)
          {
              $avg_gain[$k] = $gain[$key]*$period+(1-$period)*$avg_gain[$k-1];
              $avg_loss[$k] = $loss[$key]*$period+(1-$period)*$avg_loss[$k-1];                    
              if($avg_loss[$k] > 0)
              {
                  $rs[$k] = $avg_gain[$k]/$avg_loss[$k];
                  $rsi[$k] = 100 - (100/(1+$rs[$k])); //rsi value
              }
              else
              {
                  $rsi[$k][0] = 100; //rsi value
              }
              $k++;
          }
          if($j == ($count-1))
          break;
          $j++;           
        }
      }
      $timestamp=[];
      $close=[];
      $l=0;
      foreach($datas as $key => $data)
      {
        if($key > 13)
        {
          $timestamp[$l]=$data[6];
          $close[$l]=$data[4];
          $l++;
        }
      }      
      return array('time'=>$timestamp,'close'=>$close,'rsi'=>$rsi);
    }
    

}
