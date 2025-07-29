<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Jobs;
use App\Models\JobsShift;

class ExpiredJob extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'jobs:expired';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'To expired jobs';
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
    $jobs_model = new Jobs();
    $jobs_shift_model = new JobsShift();

    $temp = JobsShift::select('jobs1_id')
      ->selectRaw('MAX(id) as id')
      ->groupBy('jobs1_id')
      ->where('end_date', '<', Carbon::now());

    $temp1 = JobsShift::select($jobs_shift_model->get_table_name().'.*')
      ->joinSub($temp, 'temp', 'temp.id', '=', $jobs_shift_model->get_table_name().'.id');

    $arr_jobs = Jobs::select($jobs_model->get_table_name().'.*')
      ->joinSub($temp1, $jobs_shift_model->get_table_name(), $jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->where('is_live_app', '=', 1)
      ->get();

    foreach($arr_jobs as $jobs){
      $jobs->is_live_app = 0;
      $jobs->status = "closed";
      $jobs->save();
    }
  }
}
