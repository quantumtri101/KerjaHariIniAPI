<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\JobsApproveHelper;
use App\Http\Controllers\Helper\Controller\XPTransactionHelper;

use App\Models\CheckLog;
use App\Models\JobsApplication;
use App\Models\JobsShift;
use App\Models\JobsCriteria;
use App\Models\JobsInterview;
use App\Models\JobsBriefing;
use App\Models\JobsApprove;
use App\Models\JobsQualification;
use App\Models\JobsWorkingArea;
use App\Models\JobsApproveSalary;
use App\Models\JobsApproveCheckLog;
use App\Models\City;
use App\Models\User;

use App\Jobs\SendPushNotificationJob;

class JobsShiftHelper extends BaseController{
  public function check_requested_check_log($jobs_shift){
    $arr_check_log = CheckLog::where('jobs_shift_id', '=', $jobs_shift->id)->get();
    $counter = 0;
    foreach($arr_check_log as $check_log){
      if($check_log->is_approve_check_log != 'requested')
        break;
      $counter++;
    }
    if($counter == count($arr_check_log)){
      $jobs_shift->is_requested_check_log = 1;
      $jobs_shift->save();
    }
  }

  public function check_approve_check_log($jobs_shift){
    $arr_check_log = CheckLog::where('jobs_shift_id', '=', $jobs_shift->id)->get();
    $counter = 0;
    foreach($arr_check_log as $check_log){
      if($check_log->is_approve_check_log != 'approved')
        break;
      $counter++;
    }
    if($counter == count($arr_check_log)){
      $jobs_shift->is_approve_check_log = 1;
      $jobs_shift->save();
    }
  }

  public function check_requested_salary($jobs_shift){
    $arr_jobs_application = JobsApplication::where('jobs1_id', '=', $jobs_shift->jobs->id)->get();
    $counter = 0;
    foreach($arr_jobs_application as $jobs_application){
      if($jobs_application->is_approve_salary != 'requested')
        break;
      $counter++;
    }
    if($counter == count($arr_jobs_application)){
      $jobs_shift->is_requested_salary = 1;
      $jobs_shift->save();
    }
  }

  public function check_approve_salary($jobs_shift){
    $arr_jobs_application = JobsApplication::where('jobs1_id', '=', $jobs_shift->jobs->id)->get();
    $counter = 0;
    foreach($arr_jobs_application as $jobs_application){
      if($jobs_application->is_approve_salary != 'approved' || $jobs_application->is_approve_additional_salary != 'approved')
        break;
      $counter++;
    }
    if($counter == count($arr_jobs_application)){
      $jobs_shift->is_approve_salary = 1;
      $jobs_shift->save();
    }
  }
}
