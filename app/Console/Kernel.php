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
    Commands\DeleteLog::class,
    Commands\DeleteNotification::class,
    Commands\PublishJobToApp::class,
    Commands\ExpiredJob::class,
    Commands\EndedJob::class,
    Commands\AutoCheckOut::class,
  ];

  /**
  * Define the application's command schedule.
  *
  * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
  * @return void
  */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command('delete:log')->daily();
    $schedule->command('delete:notification')->daily();
    $schedule->command('jobs:publish')->everyMinute();
    $schedule->command('jobs:expired')->everyMinute();
    $schedule->command('jobs:end')->everyMinute();
    $schedule->command('jobs:auto_check_out')->everyMinute();
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
