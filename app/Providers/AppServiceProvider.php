<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ixudra\Curl\Facades\Curl;
use Auth,View;
use App\User;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
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
    public function boot()
    {
            view()->composer('*', function ($view) 
            {
              if ($view->getName() != 'moving_average' && $view->getName() != 'hr_divergance' && $view->getName() != 'index') {
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
                 if(Auth::user())
                 {

                   $totalmcap=174272191244.901;
                   $totalcapusd=Auth::user()->price_convert($totalmcap);
                   $total24hr=Auth::user()->price_convert(108201549878.489);
                 }
                 else
                 {
                    $totalmcap=174272191244.901;
                    $totalcapusd=$totalmcap;
                    $total24hr=108201549878.489;
                 }
                 
                 $dominance['btc']=65.52/100;
                 $dominance['eth']=8.25/100;
                 $dominance['alt']=(100-(65.52+8.25))/100;
               
                //View::share('hello','hai'); 
                 View::share( ['btc_price'=>$btc_price,'btc_price_change'=>$btc_price_change,'eth_price'=>$eth_price,'eth_price_change'=>$eth_price_change,'totalmcap'=>$totalmcap,'totalcapusd'=>$totalcapusd,'total24hr'=>$total24hr]);  
              }      
            });  
       
    }

}
