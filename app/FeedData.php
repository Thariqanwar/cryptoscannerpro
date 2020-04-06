<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedData extends Model
{
    protected $table = 'feed_data';

    public function feed()
    {
        return $this->belongsTo('App\Feed');
    }
    public function tags()
    {
        $tags='';
        $tags_array=[];
        

            //$tags_array=unserialize($this->category);
            $tags_array=json_decode($this->category);
            //dd($tags_array);
                if(is_array($tags_array))
                {
                    
                    //var_dump($tags_array);
                    
                   //print_r($tags_array);
                    
                    // dump($tags_array);
                    
                    // exit();

                    foreach ($tags_array as $key => $tag) {
                        
                         // dd($tag);
                        
                        if($key <3)
                        {
                            /*/$tag = preg_replace('/\s+/', '_', $tag);
                            $tag = preg_replace('/\./', '_', $tag);*/ /*replace . with _*/
                            
                            if (is_array($tag)) 
                            {
                                //dump($tag);
                            } 
                            else 
                            {
                                $tags.= $tag . ' ';
                            }
                        }
                    }
                     // dump($tags);
                    return $tags;
                
                }
                else
                {
                    //dd($tags_array);
                    return $tags_array;
                }
        
    }
    public function tagExist($tag)
    {
        //$tags_array=unserialize($this->category);
        $tags_array=json_decode($this->category);
        if(is_array($tags_array))
        {
            if(in_array($tag,$tags_array))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    public static function uniqueTags()
    {
        
        $tags=null;
        
            $data=FeedData::All();
            foreach ($data as $key1 => $each) {
                if($each->feed->isFeedEnabled())
                {
                    
                    //$tags_array=unserialize($each->category);
                    $tags_array=json_decode($each->category);
                                //dd($tags_array);
                    if(is_array($tags_array))
                    {
                        foreach ($tags_array as $key => $tag) 
                        {
                            if($key <3)
                            {
                                /*$tag = preg_replace('/\s+/', '_', $tag);
                                
                                $tag = preg_replace("/[^ \w]+/", '', $tag);*/
                                $tags[]=$tag;/*strtolower($tag);*/
                            }
                        }       
                                
                    }               
                }
                                
            }
            
            
            
            sort($tags);
            
            
            $tags=array_unique($tags);
             return array_values($tags);
    }
    public function array_iunique( $array ) {
        return array_intersect_key($array,array_unique( array_map( "strtolower", $array ) ));
    }
    public function time()
    {
        $to_time = time();
      // dd(date('Y/m/d H:i:s',$to_time));
        $from_time = strtotime(date('Y/m/d H:i:s',$this->pub_timestamp));
        if(round(abs($to_time - $from_time) / 60,0) >=10080)
        {
            return round(round(round(abs($to_time - $from_time) / 3600,0)/24,0)/7,0). ' Week';
        } 
        if(abs($to_time - $from_time) / 60 >=1380)
        {
            return round(round(abs($to_time - $from_time) / 3600,0)/24,0). ' Day';
        }
        if(round(abs($to_time - $from_time) / 60,0) >=1440)
        {
            return round(round(abs($to_time - $from_time) / 3600,0)/24,0). ' Day';
        } 
        if(round(abs($to_time - $from_time) / 60,0)>=60 && round(abs($to_time - $from_time) / 60,0)<=1400 )
        {
            return round(abs($to_time - $from_time) / 3600,0). ' Hr';
        } 
        if(round(abs($to_time - $from_time) / 60,0) <=60)
        {
            if(round(abs($to_time - $from_time) / 60,0) ==0)
            {

               return  ' Few sec';
            }
            else
            {
               return round(abs($to_time - $from_time) / 60,0). ' Min';
            }
        }
        
    }
    public function timeClass1() /*generate classes based on time*/
    {
        $to_time = time();
        $from_time =$this->pub_timestamp;
        if(round(abs($to_time - $from_time) / 60,0)<=60)
        {
            return  '1';
        } 
        
        if(round(abs($to_time - $from_time) / 60,0) <=1440)
        {
            return '24';
        } 
        if(round(abs($to_time - $from_time) / 60,0) <=2880)
        {
            return '48';
        } 
        if(round(abs($to_time - $from_time) / 60,0) <=7200)
        {
            return '5';
        } 
       
        
    }   
    public function timeClass($time) /*generate classes based on time*/
    {
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

        return $new_time;
       
        
    }   

    public function timeClass12() /*generate classes based on time*/
    {
       
        
        $to_time = time();
       

        $from_time = strtotime(date('Y/m/d H:i:s',strtotime($this->created_at)));
        $hour = abs($to_time - $from_time)/(60*60) ;
      
        if($hour<=1)
        {
             return  '1';
        }
        else if($hour<=24)
        {
            return '24';

        }
        else if($hour<=48)
        {

            return '48';
        }
        else

        {
            return '7';
        }

    }
    public function pubDate()
    {
        $date= date("d/m/Y ",strtotime($this->pub_date));
        return $date;

    }
    public function pubTime()
    {
        $time= date("H:i:s",strtotime($this->pub_date));
        return $time;
    }
                                            
                                                    
}
