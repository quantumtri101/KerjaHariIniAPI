<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Jobs;
use App\Models\JobsShift;
use App\Models\JobsApplication;
use App\Models\CheckLog;

class ReminderWorking1Day extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'reminder:working_1_day';

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
    $jobs_shift_model = new JobsShift();
    $jobs_model = new Jobs();

    $arr_shift = JobsShift::select($jobs_shift_model->get_table_name().'.*')
      ->join($jobs_model->get_table_name(), $jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->where($jobs_shift_model->get_table_name().'.start_date', 'like', Carbon::now()->addDays(1)->formatLocalized('%Y-%m-%d'))
      ->where($jobs_model->get_table_name().'.is_publish', '=', 1)
      ->where($jobs_model->get_table_name().'.is_approve', '=', 1)
      ->get();

    foreach($arr_shift as $shift){
      $arr_application = JobsApplication::where('jobs1_id', '=', $shift->jobs->id)->get();

      foreach($arr_application as $application)
        $this->communication_helper->send_push_notif($user, 'Reminder Pekerjaan', 'Mengingatkan, bahwa besok Pk. '.$shift->start_date->formatLocalized('%H:%m').', Anda terjadwal dalam Pekerjaan '.$application->jobs->name, ["id" => $application->jobs->id, 'type' => "jobs_reminder"]);
    }
  }
}
