<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubscriptionAmount;
use App\PaymentDetails;
use Auth,Redirect;
use App\Helpers\coinpayments\src\CoinpaymentsAPI;

class PaymentController extends Controller
{
    

   public function makePayment(Request $request)
   {
    //dd('d');
       $subPeriod = $request->subPeriod;
       $subType = $request->subType;
       $currency = 'LTCT';
       
       $amount = SubscriptionAmount::where('user_type', $subType)->where('period', $subPeriod)->first();
       $amount=$amount->amount;
       
       if($subType==4)
       {
        return Redirect::route('userProfile');
       }
       return view('payment.create',compact('subPeriod','subType','amount','currency'));
   }

   public function payment(Request $request)
   {
        $subPeriod = $request->subPeriod;
        $subType = $request->subType;
        $email = $request->email; 
        $payback = $request->paybackaddress;
        
        // dd($subPeriod);
        if($subType == 1)
        {
            $amt = '3.0';
        }
        elseif($subType == 2)
        {
            $amt = '2.0';
        }
        elseif($subType == 3)
        {
            $amt = '1.0';
        }
        
        $amount = SubscriptionAmount::where('user_type', $subType)->where('period', $subPeriod)->first();
        $result = $this->paymentCreate($email,$amt);
        //$result = 0;
        $data = $result['result'];
        // $txn_id = $result['result']['txn_id'];
        if($result['error'] == 'ok')
        {
            $this->savePaymentDetails($result['result'],$subPeriod,$subType,$email,$payback);
        }

        return view('payment.details',compact('data','email','amount'));
        
   }

   public function getPaymentStatus(Request $request)
   {
    $txn_id = $request->txn_id;
    $startTime = date("Y-m-d H:i:s");
    $endTime = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($startTime)));
    $private_key = '3a76A8eBf75c2034c58BB0dd2A914d24d3354D3367eCd597277AF58a2a419e89';
    $public_key = 'a873b14b4f33c9d0571f3b685e10fd5bdbbf51a4b4fd002e09a626c717208e0d';
    $cps_api = new CoinpaymentsAPI($private_key, $public_key, 'json');  
    ini_set('max_execution_time', 3600);
    while(strtotime(date("Y-m-d H:i:s")) < strtotime($endTime))
    {
        $status = $cps_api->GetTxInfoSingle($txn_id);
        echo '<pre>' . var_export($status, true) . '</pre>';
        if($status['error'] == 'ok')
        {
            if($status['result']['status'] >= 100 || $status['result']['status'] == 2)
            {
                // return $status['result'];
                // $flag = 1;
                break;
            }
        }    
        // echo "checking Payment";
        // echo $result['result']['address'];
        sleep(1);
    }
    return json_encode($status['result']);
    // echo ($flag)?"Payment completed":"Payment failed";
   }
    public function paymentCreate($buyer_email,$amount)
    {
        $private_key = '3a76A8eBf75c2034c58BB0dd2A914d24d3354D3367eCd597277AF58a2a419e89';
        $public_key = 'a873b14b4f33c9d0571f3b685e10fd5bdbbf51a4b4fd002e09a626c717208e0d';
        $cps_api = new CoinpaymentsAPI($private_key, $public_key, 'json');

        // Enter amount for the transaction
        // $amount = 2.0;

        // Litecoin Testnet is a no value currency for testing
        $currency = 'LTCT';

        // Enter buyer email below
        // $buyer_email = 'sreenath.sahadevan@cordiace.com';

        // Make call to API to create the transaction
        try {
            $transaction_response = $cps_api->CreateSimpleTransaction($amount, $currency, $buyer_email);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            exit();
        }
        if ($transaction_response['error'] == 'ok') {
            // Success!
            return $transaction_response;
            // $output = 'Transaction created with ID: ' . $transaction_response['result']['txn_id'] . '<br>';
            // $output .= 'Amount for buyer to send: ' . $transaction_response['result']['amount'] . '<br>';
            // $output .= 'Address for buyer to send to: ' . $transaction_response['result']['address'] . '<br>';
            // $output .= 'Seller can view status here: ' . $transaction_response['result']['status_url'];
        
        } else {
            // Something went wrong!
            $output = 'Error: ' . $transaction_response['error'];
            return $output;
        }
        
    }

    public function savePaymentDetails($result,$subPeriod,$subType,$email,$payback)
    {   
        $model = new PaymentDetails();
        $private_key = '3a76A8eBf75c2034c58BB0dd2A914d24d3354D3367eCd597277AF58a2a419e89';
        $public_key = 'a873b14b4f33c9d0571f3b685e10fd5bdbbf51a4b4fd002e09a626c717208e0d';
        $cps_api = new CoinpaymentsAPI($private_key, $public_key, 'json');

        $status = $cps_api->GetTxInfoSingle($result['txn_id']);

        while($status['error'] != 'ok')
        {
            $status = $cps_api->GetTxInfoSingle($result['txn_id']);
            sleep(1);
        }
        $userId = Auth::user()->id;
        
        $model->user_id = $userId;
        $model->email = $email;
        $model->payment_status = $status['result']['status'];
        $model->txn_id = $result['txn_id'];
        $model->start_time = $status['result']['time_created'];
        $model->expire_time = $status['result']['time_expires'];
        $model->amount = $status['result']['amountf'];
        $model->payment_address = $result['address'];
        $model->payback_address = $payback;
        $model->subscription_type = $subType;
        $model->subscription_period = $subPeriod;
        $model->coin_type = 'LTCT';
        $model->save();

    }

    // public function getPaymentStatus()
    // {
    //      // Fill these in with the information from your CoinPayments.net account.
    // $cp_merchant_id = 'e418f40ed9ff5a6f1e78eb206a327f09';
    // $cp_ipn_secret = 'OFYQ6ALKE5';
    // $cp_debug_email = 'cordiacetechnologies@gmail.com';

    // //These would normally be loaded from your database, the most common way is to pass the Order ID through the 'custom' POST field.
    // $order_currency = 'LTCT';
    // $order_total = 0.005;

    // function errorAndDie($error_msg) {
    //     global $cp_debug_email;
    //     if (!empty($cp_debug_email)) {
    //         $report = 'Error: '.$error_msg."\n\n";
    //         $report .= "POST Data\n\n";
    //         foreach ($_POST as $k => $v) {
    //             $report .= "|$k| = |$v|\n";
    //         }
    //         mail($cp_debug_email, 'CoinPayments IPN Error', $report);
    //     }
    //     die('IPN Error: '.$error_msg);
    // }

    // if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
    //     errorAndDie('IPN Mode is not HMAC');
    // }

    // if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
    //     errorAndDie('No HMAC signature sent.');
    // }

    // $request = file_get_contents('php://input');
    // if ($request === FALSE || empty($request)) {
    //     errorAndDie('Error reading POST data');
    // }
    // dd($request);

    // if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($cp_merchant_id)) {
    //     errorAndDie('No or incorrect Merchant ID passed');
    // }

    // $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
    // if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
    // //if ($hmac != $_SERVER['HTTP_HMAC']) { <-- Use this if you are running a version of PHP below 5.6.0 without the hash_equals function
    //     errorAndDie('HMAC signature does not match');
    // }
    
    // // HMAC Signature verified at this point, load some variables.

    // $txn_id = $_POST['txn_id'];
    // $item_name = $_POST['item_name'];
    // $item_number = $_POST['item_number'];
    // $amount1 = floatval($_POST['amount1']);
    // $amount2 = floatval($_POST['amount2']);
    // $currency1 = $_POST['currency1'];
    // $currency2 = $_POST['currency2'];
    // $status = intval($_POST['status']);
    // $status_text = $_POST['status_text'];

    // //depending on the API of your system, you may want to check and see if the transaction ID $txn_id has already been handled before at this point

    // // Check the original currency to make sure the buyer didn't change it.
    // if ($currency1 != $order_currency) {
    //     errorAndDie('Original currency mismatch!');
    // }    
    
    // // Check amount against order total
    // if ($amount1 < $order_total) {
    //     errorAndDie('Amount is less than order total!');
    // }

    // return $status;
  
    // if ($status >= 100 || $status == 2) {
    //     // payment is complete or queued for nightly payout, success
    // } else if ($status < 0) {
    //     //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
    // } else {
    //     //payment is pending, you can optionally add a note to the order page
    // }
    // }
}
