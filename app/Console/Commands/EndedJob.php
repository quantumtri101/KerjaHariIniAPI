<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Jobs;

class EndedJob extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'jobs:end';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'To publish jobs';
  private $base;

  /**
  * Create a new command instance.
  *
  * @return void
  */
  public function __construct()
  {
    parent::__construct();
    $this->base = new BaseController();
  }

  /**
  * Execute the console command.
  *
  * @return int
  */
  public function handle()
  {
    $arr_jobs = Jobs::where('status', '!=', 'ended')->get();

    foreach($arr_jobs as $jobs){
      $counter = 0;
      foreach($jobs->shift as $shift){
        if($shift->end_date > Carbon::now())
          break;
        $counter++;
      }

      if($counter == count($jobs->shift)){
        $jobs->status = 'ended';
        $jobs->save();
      }
    }
  }
}
