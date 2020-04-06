<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Test;
use App\Helpers\AppHelper;
use App\Model\Telegram_chat;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use Shortener; 
use Twitter;
use Helper;
use Mail;


class BotController extends Controller{  
   //Bot Commands    
    public function bot_commands(Request $request){ 
       

       /* $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,'https://api.telegram.org/bot743896776:AAFC2nou6yKHom3-trRtdYar_7mEU3iFTE8/sendMessage?chat_id=573420013&text=hai');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);*/
        //file_get_contents('https://api.telegram.org/bot743896776:AAFC2nou6yKHom3-trRtdYar_7mEU3iFTE8/sendMessage?chat_id=573420013&text='.$s);
        $content = file_get_contents('php://input');        
        $contents = json_decode($content, TRUE);
        $test= new Test;
        $test->test=$content;
        $test->save();
//        $authorize = fopen("./storage/logs/bot.txt", "a+") or die("Unable to open file!");                     
//        $arb_data = $content. chr(10); 
//        fwrite($authorize, $arb_data);        
//        fclose($authorize);  
        
        if(!empty($contents["message"])){
            AppHelper::updateTelegramChatId($content);
            $chatId = $contents["message"]["chat"]["id"];                         
            if(array_key_exists('text',$contents["message"])){ 
                $command_text = ($contents["message"]["text"]) ? $contents["message"]["text"]:'';
                //$message = explode("@",$command_text);   
                $bot_command = explode(" ",$command_text);  
                $command=strtolower($bot_command[0]);

                if($command=='/start') 
                {
                    $text = 'Hello!  I am the official Cryptoscanner bot. My mission is to Help you to make your Subscription'.chr(10);
                    AppHelper::sendMessage($chatId, $text);
                    $text = 'Enter Your Email Address in below format .'.chr(10); 
                    $text.= ' /email your_mail_address  '.chr(10).chr(10); 
                    $text.= 'Ex: /email yourmail@gmail.com '.chr(10); 
                    AppHelper::sendMessage($chatId, $text);
                    
                } 
                if($command=='/email') 
                {
                       
                                
                    if(empty($bot_command[1]))
                    {
                        $text = ' Invalid command format.'.chr(10);
                        $text.= 'Use Correct Command : /email your_mail_address  '.chr(10).chr(10);
                        AppHelper::sendMessage($chatId, $text);
                    }
                    else
                    {
                        if(User::where(['email'=>$bot_command[1]])->exists())
                        {
                            $telegram=Telegram_chat::where('chat_id',$chatId)->first();
                            $exist=Telegram_chat::where('chat_id','<>',$chatId)->where('status',1)->where('verify_email',$bot_command[1])->first();
                            $exist2=Telegram_chat::where('chat_id',$chatId)->where('status',1)->where('verify_email',$bot_command[1])->first();
                             if($exist2 )
                            {
                                $text = 'This telegram account is already active .'.chr(10); 
                                AppHelper::sendMessage($chatId, $text);
                            }
                            elseif($exist )
                            {
                               
                                $text = 'This email is already used by another telegram account .'.chr(10); 
                                $text.= ' verify your identity to activate this telegram account with ' .$bot_command[1] .chr(10).chr(10);
                                $text.= 'Check your email -' .$bot_command[1] .chr(10).chr(10);
                                $data = array('exist'=>$exist);
                                $to=$bot_command[1];
                                Mail::send(['text'=>'telegram_mail'], $data, function($message) use($to) {
                                    $message->to('arshad.ka5@gmail.com', 'Telegram activation')->subject('Telegram activation');
                                    $message->from('_mainaccount@cryptoscannerpro.com','cryptoscannerpro');
                                });
                                AppHelper::sendMessage($chatId, $text);
                            }
                            else
                            {

                                $telegram->verify_email=$bot_command[1];
                                $telegram->save();

                                $text = 'Enter Your passcode in below format .'.chr(10); 
                                $text.= ' /passcode your_passcode  '.chr(10).chr(10); 
                                $text.= 'Ex: /passcode Ehr7Ty4b4 '.chr(10); 
                                AppHelper::sendMessage($chatId, $text);
                            }

                        }
                        else
                        {
                            $text ='Account not registerd with this email. please contact administrator'.chr(10);
                            AppHelper::sendMessage($chatId, $text);
                        }

                    }

                }
                 if($command=='/passcode') 
                {
                    if(empty($bot_command[1]))
                    {
                        $text = $command_text.'Invalid command format.'.chr(10);
                        $text.= 'Use Correct Command : /passcode your_passcode  '.chr(10).chr(10);
                        AppHelper::sendMessage($chatId, $text);
                    }
                    else
                    {
                        $telegram=Telegram_chat::where('chat_id',$chatId)->first();
                        if($telegram->verify_email!==null)
                        {
                            if(User::where(['email'=>$telegram->verify_email,'password_string'=>$bot_command[1]])->exists())
                            {
                                $user=User::where(['email'=>$telegram->verify_email,'password_string'=>$bot_command[1]])->first();
                                $user->telegram_chat_id=$telegram->id;
                                $user->save();
                                $telegram->status=1;
                                $telegram->save();
                                $text =  'Success. Your Account is verified Successfully.'.chr(10);
                                $text.= 'Please <a href="https://cryptoscannerpro.com/login">Login</a> to change Your preferences '.chr(10);
                                AppHelper::sendMessage($chatId, $text);
                            }
                            else
                            {
                                $text =  'Invalid email or password'.chr(10);
                                AppHelper::sendMessage($chatId, $text);
                            }
                        }
                        else
                        {
                            $text = 'Enter Your Email Address in below format .'.chr(10); 
                            $text.= ' /email your_mail_address  '.chr(10).chr(10); 
                            $text.= 'Ex: /email yourmail@gmail.com '.chr(10); 
                            AppHelper::sendMessage($chatId, $text);
                            
                        }
                       
                            
                       
                    }
                        

                }
               
            }

        }else if(!empty ($contents["callback_query"])){            
            $chatId = $contents["callback_query"]["message"]["chat"]["id"];
            $user_id = $contents["callback_query"]["from"]["id"];
            $message_id = $contents["callback_query"]["message"]["message_id"];
            $message = $contents["callback_query"]["message"]["text"];
            
            $username = isset($contents["callback_query"]["from"]["username"]) ? $contents["callback_query"]["from"]["username"]:'';
            $last_name = isset($contents["callback_query"]["from"]["last_name"]) ? $contents["callback_query"]["from"]["last_name"] : '';
            $full_name = isset($contents["callback_query"]["from"]["first_name"]) ? $contents["callback_query"]["from"]["first_name"].' '.$last_name:'';
            switch($contents['callback_query']['data']){
                default:                     
                    return;
            }             
        }else{
            return;
        }                        
        return;
    }
    public function getTelegramId(){
        $Token="743896776:AAFC2nou6yKHom3-trRtdYar_7mEU3iFTE8";
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,'https://api.telegram.org/bot'.$Token.'/getUpdates');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'cryptoscannerpro');
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        print_r($response);die;
        $subscribers = json_decode($response);

        foreach ($subscribers->result as $key => $value) {
           // print_r($value->message);
            $user=User::where('password',$value->message->text)->where('telegram_id',NULL)->first();
            //print_r($value->message->chat);
            if($user){

                $user->telegram_id=$value->message->chat->id;
                $user->telegram_username=$value->message->chat->first_name;
                $user->status=1;
                $user->save();
                $txt="Hi ".$value->message->chat->first_name.", Your account verified successfullyy";
                $send=AppHelper::sendMessage_one_one($txt,$value->message->chat->id,$Token);
                print_r($send);

            }
        }
    }
}
                
    