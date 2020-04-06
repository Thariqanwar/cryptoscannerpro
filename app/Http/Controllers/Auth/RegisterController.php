<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\SubscriptionPeriod;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Endroid\QrCode\QrCode;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    use RegistersUsers; /*{*/
        // change the name of the name of the trait's method in this class
        // so it does not clash with our own register method
         /*  register as registration;*/
       /*}*/
   

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user/subscribe';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $subPeriod = 1;
        $subType = 4;
        $sub_periods = SubscriptionPeriod::find($subPeriod);
             if($sub_periods)
             {
                $d=strtotime("+".$sub_periods->text);
             }
       

       
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
           /* 'google2fa_secret' => $data['google2fa_secret'],*/
            'password' => Hash::make($data['password']),
            'user_type' => 'user',
            'subscription_type' => $subType,
            'status' => 1,
            'login_status' => 0,
            'password_string' => $data['password'],
            'subscription_period' => $subPeriod,
            'subscription_start' => date("Y-m-d h:i:sa"),
            'subscription_end' => date("Y-m-d h:i:sa", $d),
        ]);
       
    }

    public function register1(Request $request)/* remove 1*/
    {
        //Validate the incoming request using the already included validator method
        $this->validator($request->all())->validate();

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
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );  

        $qrCode = new \Endroid\QrCode\QrCode($QR_Image);
        $qrCode->setSize(100);
        $google2fa_url = $qrCode->writeDataUri();

        // Pass the QR barcode image to our view        
        return view('google2fa.register', ['QR_Image' => $google2fa_url, 'secret' => $registration_data['google2fa_secret']]);
    }

    public function completeRegistration(Request $request)
    {        
        // add the session data back to the request input
        // dd(session('registration_data'));
       $request->merge(session('registration_data'));

        // Call the default laravel authentication
        return $this->registration($request);
    }

}
