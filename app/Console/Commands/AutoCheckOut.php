<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Jobs;
use App\Models\JobsShift;
use App\Models\JobsApplication;
use App\Models\CheckLog;

class AutoCheckOut extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'jobs:auto_check_out';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'To auto check out on expired jobs';
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
    $jobs_application_model = new JobsApplication();
    $jobs_shift_model = new JobsShift();

    $arr_application = JobsApplication::select($jobs_application_model->get_table_name().'.*', $jobs_shift_model->get_table_name().'.id as jobs_shift_id',)
      ->join($jobs_shift_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_shift_model->get_table_name().'.jobs1_id')
      ->where($jobs_application_model->get_table_name().'.status', '!=', 'done')
      ->where($jobs_application_model->get_table_name().'.status', '!=', 'expired')
      ->where($jobs_application_model->get_table_name().'.status', '!=', 'declined')
      ->where($jobs_shift_model->get_table_name().'.end_date', '<', Carbon::now()->subHours(1))
      ->get();
    // dd($arr_shift);

    // foreach($arr_shift as $shift){
    //   $arr_application = JobsApplication::where('jobs1_id', '=', $shift->jobs->id)->get();

      foreach($arr_application as $application){
        if($application->status == "working"){
          $check_log = CheckLog::where('jobs_application_id', '=', $application->id)
            ->where('user_id', '=', $application->user->id)
            ->where('jobs_shift_id', '=', $application->jobs_shift_id)
            ->where('type', '=', 'check_out')
            ->first();
          
          if(empty($check_log)){
            $check_log = new CheckLog();
            $check_log->jobs_application_id = $application->id;
            $check_log->jobs1_id = $application->jobs->id;
            $check_log->jobs_shift_id = $application->jobs_shift_id;
            $check_log->user_id = $application->user->id;
            $check_log->type = 'check_out';
            $check_log->date = Carbon::now();
            $check_log->date_init = Carbon::now();
            $check_log->created_by = 'system';
            $check_log->updated_by = 'system';
            $check_log->save();

            $application->status = 'done';
            $application->save();

            $user = $application->user;
            $user->is_working = 0;
            $user->save();
          }
        }
        else if($application->status == "accepted"){
          $application->status = 'expired';
          $application->save();
        }
        else if($application->status == "wait" || $application->status == "interview"){
          $application->status = 'declined';
          $application->save();
        }
      }
    // }
  }
}
