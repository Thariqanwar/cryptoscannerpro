<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Subscription;
use App\SubscriptionPeriod;
use App\RsiTimeFrame;
use Redirect;
use Hash,Validator;
use App\Helpers\AppHelper;
use App\AlertLog;
use App\Feed;
use App\FeedData;
use App\FeedsCategory;
use SimpleXMLElement;



class MainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
     public function Feed()
    {
    	/*$all_feeds=Feed::orderBy('id','DESC')->get();
        //$content = file_get_contents('http://rss.leparisien.fr/leparisien/rss/paris-75.xml');
        foreach ($all_feeds as $key => $feed) {
        
         		$ch = curl_init($feed->feed_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);
       
       			
                $data=simplexml_load_string($data,null,LIBXML_NOCDATA);
       			$data=$this->objectsIntoArray($data);
       			//dd($d['channel']);
       			$data['channel']['category']=$feed->name;
       			//dd($data['channel']);
       			//$data->channel->category=$feed->name;
                $feeds[]=$data['channel'];
        }     */
     	//dd($feeds);
     	/*$feed_list=Feed::All();
        $feeddata=FeedData::All();
        return view('feed')->with('feeds',$feeds)->with('feed_list',$feed_list);
*/

        $categories=FeedsCategory::All(); /*All categories*/
        $all_feeds=FeedData::orderBy('pub_timestamp','DESC')->get();
        $feed_provider=Feed::All();
        $feed_tags=FeedData::uniqueTags();
        return view('feed')->with(['feeds'=>$all_feeds,'all_feeds'=> $feed_provider,'categories'=>$categories,'feed_tags'=>$feed_tags]);
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
}
