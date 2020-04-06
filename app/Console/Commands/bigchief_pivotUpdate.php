<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use \App\Model\Pivot;
use Helper;

class bigchief_pivotUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pivot:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pivot Update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        //Pivot Point Update
        $response = Curl::to('https://api.binance.com/api/v1/ticker/24hr')->withContentType('application/json')->get();
        $full_datas     = json_decode($response); 
        Pivot::query()->truncate();
        foreach($full_datas as $full_data){                
            if(((substr($full_data->symbol, -3) == 'BTC') || (substr($full_data->symbol, -4) == 'USDT'))){ 
                $response = Curl::to('https://api.binance.com/api/v1/klines?symbol='.$full_data->symbol.'&interval=1d&limit=2')->withContentType('application/json')->get();
                $datas = json_decode($response,TRUE);
                $data = $datas[0];
                $pivot_point = ($data[2]+$data[3]+$data[4])/3;   //$data[2] -> high, $data[3] -> low, $data[4] -> close
                $resistance1 = 2*$pivot_point - $data[3];
                $support1 = 2*$pivot_point - $data[2];
                $resistance2 = $pivot_point+($data[2]-$data[3]);
                $support2 = $pivot_point-($data[2]-$data[3]);
                $resistance3 = $data[2] + 2*($pivot_point - $data[3]);
                $support3 = $data[3] - 2*($data[2] - $pivot_point);
                
                $p_obj = new Pivot();
                $p_obj->symbol = $full_data->symbol;
                $p_obj->pivot_point = number_format($pivot_point,($pivot_point > 1) ? 4:8,'.','');
                $p_obj->resistance1 = number_format($resistance1,($resistance1 > 1) ? 4:8,'.','');
                $p_obj->resistance2 = number_format($resistance2,($resistance2 > 1) ? 4:8,'.','');
                $p_obj->resistance3 = number_format($resistance3,($resistance3 > 1) ? 4:8,'.','');
                $p_obj->support1 = number_format($support1,($support1 > 1) ? 4:8,'.','');
                $p_obj->support2 = number_format($support2,($support2 > 1) ? 4:8,'.','');
                $p_obj->support3 = number_format($support3,($support3 > 1) ? 4:8,'.','');
                $p_obj->timestamp = strtotime(date('Y-m-d G:i:s'));
                $p_obj->created_on = date('Y-m-d G:i:s');          
                $p_obj->save(); 
            }             
        }        
    }
}
