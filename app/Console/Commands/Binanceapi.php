<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use App\Helpers\AppHelper;
use \App\Model\Cron_status;
use \App\Http\Controllers\CommonController;

class Binanceapi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binance:apicall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Binance api call';

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
        /*$cron=Cron_status::first();
        if($cron->status==1){*/
            CommonController::binanceApicall();
        /*}*/
        
    }
}

 ?>