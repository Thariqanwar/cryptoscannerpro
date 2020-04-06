<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use App\Helpers\AppHelper;
use \App\FeedData;
use \App\Feed;

class RssFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rss feed saving';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
        $days=strtotime('now -5 days');
        $oldfeed=FeedData::where('pub_timestamp','<',$days)->get();
        foreach ($oldfeed as $key => $delvalue) {
           $delvalue->delete();
        }
        $feed_list=Feed::all();
        $all_feeds=Feed::orderBy('id','DESC')->get();
        foreach ($all_feeds as $key => $feed) 
        {
            $ch = curl_init($feed->feed_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            curl_close($ch);
            if($feed->category=='6' && $feed->feed_url=="http://www.cryptoscannerpro.com/RssFeed") 
            {

                $path=public_path()."/uploads/blog/feed.xml";
                $data=file_get_contents($path);    
            }                            
            if(substr($data, 0,5)=='<?xml' || $feed->feed_url=="http://www.cryptoscannerpro.com/RssFeed")
            {
                $invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
                $data = preg_replace($invalid_characters, '', $data);
                $data=simplexml_load_string($data,null,LIBXML_NOCDATA);
                $data=$this->objectsIntoArray($data);
                if($feed->category=='1' || $feed->category=='11') 
                {
                    if(isset($data['channel']['item']))
                    {

                        foreach($data['channel']['item'] as $item)
                        {
                            if(isset($item['title']) && isset($item['link']) )
                            {

                                if(!FeedData::where('title',$item['title'])->exists())
                                {
                                    $feed_data=new FeedData;
                                    $feed_data->feed_id = $feed->id; 
                                    //dd($item['title']); 
                                   

                                    $feed_data->title = $item['title'];
                                  
                                   
                                    $feed_data->link = $item['link'];
                                   
                                    if(!isset($item['pubDate'])) {  $feed_data->pub_date =time();} else{   $feed_data->pub_date = $item['pubDate']; } 

                                    $feed_data->pub_timestamp=strtotime($item['pubDate']);
                                    if(time() < $feed_data->pub_timestamp) { continue;}
                                    if(!empty($item['category']))
                                    {
                                        $feed_data->category = serialize( $item['category']);
                                    }
                                    else
                                    {
                                        $c[]=$data['channel']['title'];
                                        if(empty($data['channel']['title']))
                                        {
                                          if($feed->category=='11')
                                          {
                                            $c[]='news';  
                                          }
                                          else
                                          {

                                            $c[]='crypto-news';
                                          }
                                        }
                                        $feed_data->category = serialize($c);
                                    }
                                    if(!empty($item['description'])) 
                                    {
                                        $feed_data->description = serialize($item['description']);
                                    }
                                    if($feed_data->pub_timestamp > $days)
                                    {
                                    
                                        $feed_data->save();
                                    }   
                                }
                            }
                            else
                            {
                               
                                continue;
                            }
                        }    
                    }
                }  
                if($feed->category=='8') 
                {
                    if(isset($data['channel']['item']))
                    {

                        foreach($data['channel']['item'] as $item)
                        {
                            if(isset($item['title']) && isset($item['link']) )
                            {

                                if(!FeedData::where('title',$item['title'])->exists())
                                {
                                    $feed_data=new FeedData;
                                    $feed_data->feed_id = $feed->id; 
                                    //dd($item['title']);       
                                    $feed_data->title = $item['title'];
                                    $feed_data->link = $item['link'];
                                    if(!isset($item['pubDate'])) { continue;  $feed_data->pub_date =time(); $feed_data->pub_timestamp =time();} else{   $feed_data->pub_date = $item['pubDate']; } 
                                    $feed_data->pub_timestamp=strtotime($item['pubDate']);
                                    if(time() < $feed_data->pub_timestamp) { continue;}
                                    if(!empty($item['category']))
                                    {
                                        $feed_data->category = serialize( $item['category']);
                                    }
                                    else
                                    {
                                        $c[]=$data['channel']['title'];
                                        if(empty($data['channel']['title']))
                                        {
                                          $c[]='medium';
                                        }
                                        $feed_data->category = serialize($c);
                                    }
                                    if(!empty($item['description'])) 
                                    {
                                        $feed_data->description = serialize($item['description']);
                                    }
                                    if($feed_data->pub_timestamp > $days)
                                    {
                                    
                                        $feed_data->save();
                                    }   
                                }
                            }
                            else
                            {
                                continue;
                            }
                        }    
                    }
                }                      
                if($feed->category=='6' && $feed->feed_url=="http://www.cryptoscannerpro.com/RssFeed") 
                {
                    if(isset($data['item']))
                    {
                        foreach($data['item'] as $item)
                        {
                            if(isset($item['title']) && isset($item['link']) )
                            {
                                if(!FeedData::where('title',$item['title'])->exists())
                                {
                                   $feed_data=new FeedData;
                                    $feed_data->feed_id = $feed->id; 
                                    $feed_data->title = $item['title'];
                                    $feed_data->link = $item['link'];
                                    $feed_data->pub_date = $item['pubDate'];
                                    $feed_data->pub_timestamp=strtotime($item['pubDate']);
                                    if(time() < $feed_data->pub_timestamp) { continue;}
                                    $feed_data->category =  $item['category'];
                                    $feed_data->description = serialize($item['description']);

                                    if($feed_data->pub_timestamp > $days)
                                    {
                                        $feed_data->save();
                                    }                             
                                }
                            }
                            else
                            {
                                continue;
                            } 
                        }
                    }              
                }                        
                if($feed->category=='6' && $feed->feed_url!="http://www.cryptoscannerpro.com/RssFeed")
                {
                      if(isset($data['channel']['item']))
                      {
                        foreach($data['channel']['item'] as $item)
                        {
                          if(isset($item['title']) && isset($item['link']) )
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
                                if(time() < $feed_data->pub_timestamp) { continue;}
                                if(!empty($item['category']))
                                {
                                    $feed_data->category = serialize( $item['category']);
                                }
                                else
                                {
                                    $c[]=$data['channel']['title'];
                                    if(empty($data['channel']['title']))
                                    {
                                      $c[]='blog';
                                    }
                                    $feed_data->category = serialize($c);
                                }
                                if(!empty($item['description'])) 
                                {
                                    $feed_data->description = serialize($item['description']);
                                }
                                if($feed_data->pub_timestamp > $days)
                                {

                                    $feed_data->save();
                                }
                            }  
                          }
                          else
                          {
                              continue;
                          }   
                        }
                      }
                }
                if($feed->category=='9') 
                {
                      if(isset($data['channel']['item']))
                      {

                          foreach($data['channel']['item'] as $item)
                          {   
                              if(isset($item['title']) && isset($item['link']) )
                              {

                                if(!FeedData::where('title',$item['title'])->exists())
                                {
                                   
                                    $feed_data=new FeedData;
                                    $feed_data->feed_id =$feed->id; 
                                          
                                    $feed_data->title = $item['title'];
                                    $feed_data->link = $item['link'];
                                    $feed_data->pub_date = $item['pubDate'];
                                    $feed_data->pub_timestamp=strtotime($item['pubDate']);
                                    if(time() < $feed_data->pub_timestamp) { continue;}
                                     
                                    if(!empty($item['category']))
                                    {
                                        $feed_data->category = serialize( $item['category']);
                                    }
                                    else
                                    {
                                        $c[]="twitter ".$data['channel']['title'];
                                        $feed_data->category = serialize($c);
                                    }

                                    if(!empty($data['channel']['description'])) 
                                    {

                                        $feed_data->description = serialize($data['channel']['description']);
                                    }
                                    else
                                    {
                                        $c[]=$data['channel']['title'];
                                        if(empty($data['channel']['title']))
                                        {
                                          $c[]='twitter';
                                        }
                                        $feed_data->category = serialize($c);
                                    }
                                  
                                   
                                    if($feed_data->pub_timestamp > $days)
                                    {

                                        $feed_data->save();
                                    }

                                }
                              }
                              else
                              {
                                  continue;
                              }
                          }    
                      }
                }                          
                if($feed->category=='7')  
                {
                   
                    if(isset($data['channel']['item']))
                    {
                        foreach($data['channel']['item'] as $item)
                        {   
                            if(isset($item['title']) && isset($item['link']) )
                            {

                              if(!FeedData::where('title',$item['title'])->exists())
                              {
                                 
                                  $feed_data=new FeedData;
                                  $feed_data->feed_id =$feed->id; 
                                        
                                  $feed_data->title = $item['title'];
                                  $feed_data->link = $item['link'];
                                  $feed_data->pub_date = $item['pubDate'];
                                  $feed_data->pub_timestamp=strtotime($item['pubDate']);
                                  if(time() < $feed_data->pub_timestamp) { continue;}
                                   
                                  if(!empty($item['category']))
                                  {
                                      $feed_data->category = serialize( $item['category']);
                                  }
                                  else
                                  {
                                      $c[]="yotube ".$data['channel']['title'];
                                      $feed_data->category = serialize($c);
                                  }

                                  if(!empty($data['channel']['description'])) 
                                  {

                                      $feed_data->description = serialize($data['channel']['description']);
                                  }
                                  else
                                  {
                                      $c[]=$data['channel']['title'];
                                      if(empty($data['channel']['title']))
                                      {
                                        $c[]='youtube';
                                      }
                                      $feed_data->category = serialize($c);
                                  }
                                
                                 
                                  if($feed_data->pub_timestamp > $days)
                                  {

                                      $feed_data->save();
                                  }
                              }
                            }
                            else
                            {
                                continue;
                            }
                        }       
                    }

                   
                    if(isset($data['entry']))
                    {
                        foreach($data['entry'] as $item)
                        {


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
                                  if(time() < $feed_data->pub_timestamp) { continue;}
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
                                  
                                  if($feed_data->pub_timestamp > $days)
                                  {

                                      $feed_data->save();
                                  }
                              }
                            }
                        }  
                    }  
                } 
                if($feed->category=='10') /*reddit*/                      
                {
                  if(isset($data['entry']))
                  {
                      foreach($data['entry'] as $item)
                      {


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
                                
                                
                               
                                $feed_data->pub_date = $item['updated'];
                                $feed_data->pub_timestamp=strtotime($item['updated']);
                                if(time() < $feed_data->pub_timestamp) { continue;}
                                $category=["reddit",$item['author']['name']];
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
                                
                                if($feed_data->pub_timestamp > $days)
                                {

                                    $feed_data->save();
                                }
                            }
                          }
                      }  
                  }    
                }
            }                                                                   
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
    public function test($message)
    {

        $botToken="979168781:AAFa7WMSZynf26kYsJdPATzPqLc1N-DgTCs";
        $message= $message;
        $chat_id='-1001490750483';
        $url="https://api.telegram.org/bot$botToken/sendMessage?chat_id=".$chat_id."&text=".$message;
        //dd($url); 
        $ch = curl_init($url);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                      $data = curl_exec($ch);
                      //dd($data);
                      curl_close($ch);   
                      return true;                         
    }
}

?>