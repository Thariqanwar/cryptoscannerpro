<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
       'App\Console\Commands\lowprice',
       'App\Console\Commands\verify',
       'App\Console\Commands\HorizonatlDivergence',
       'App\Console\Commands\MovingAverage',
       'App\Console\Commands\bigchief_closingIco',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('low:price')
            ->everyMinute();
        $schedule->command('verify:account')
            ->everyMinute();
    
        $schedule->command('horizontal:support')->everyMinute();//bigchief
        $schedule->command('moving:average')->everyMinute();//bigchief

        $schedule->command('closing:ico')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
