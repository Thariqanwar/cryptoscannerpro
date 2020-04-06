<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubscriptionPeriod;
use App\UserCategory;
use Auth;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('max_execution_time', 0);
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        // dd("subscribe here");
        $periods = SubscriptionPeriod::all();
        $types = UserCategory::all();
        $subPeriod = $request->period;
        $subType = $request->type;

        if($request->type == 4)
        {
            $user = Auth::user(); 
            $user->subscription_type = $subType;
            $user->save();
            return redirect('/user');
        }

        if($request->period && $request->type)
        {
            return redirect()->route('makePayment',['subPeriod' => $subPeriod,'subType' => $subType]) ;
        }
        
        return view('subscription.create',compact('periods','types'));//->with('sub_periods',$sub_periods);
    }
}
