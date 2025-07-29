<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Jobs;

class PublishJobToApp extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'jobs:publish';

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
    $arr_jobs = Jobs::where('status', '!=', 'ended')
      ->where('is_approve', '=', 1)
      ->orderBy('created_at', 'desc')
      ->get();

    
    foreach($arr_jobs as $jobs){
      $jobs->is_live_app = $jobs->publish_start_at <= Carbon::now() && $jobs->publish_end_at >= Carbon::now() ? 1 : 0;
      $jobs->save();
    }
  }
}
