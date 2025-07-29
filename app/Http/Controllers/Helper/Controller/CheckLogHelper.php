<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\JobsApplicationSalaryHelper;

use App\Models\Event;
use App\Models\EventImage;
use App\Models\User;
use App\Models\CheckLogDocument;

class CheckLogHelper extends BaseController{
  public function add_document($arr_image, $check_log){
    foreach($arr_image as $image){
      $data = new CheckLogDocument();
      $data->check_log_id = $check_log->id;
      $data->save();

      $this->file_helper->manage_file($image, $data, 'check_log_document', 'file_name', 'mime_type');
      $data->save();
    }
  }

  public function sent_salary($check_log){
    $jobs_application_salary_helper = new JobsApplicationSalaryHelper();

    if($check_log->type == 'check_out'){
      $jobs = $check_log->jobs_application->jobs;
      if($jobs->type == 'one-time')
        $jobs_application_salary_helper->sent_all_salary($check_log->jobs_application);
      else if($jobs->type == 'regular'){
        $arr_date = [];
        $counter_date = $check_log->jobs_application->jobs->start_date;
        $limit_date = $check_log->jobs_application->jobs->end_date;
        while(!$counter_date->isSameDay($limit_date)){
          array_push($arr_date, [
            "date" => $counter_date,
            "isChecked" => false,
          ]);
          $counter_date->add(1, 'd');
        }

        $arr_check_log = CheckLog::where('jobs_application_id', '=', $check_log->jobs_application->id)->orderBy('date', 'asc')->get();
        foreach($arr_date as $key => $date){
          foreach($arr_check_log as $check_log){
            if($check_log->date->isSameDay($date["date"])){
              $arr_date[$key]["isChecked"] = true;
              break;
            }
          }
        }

        $counter = 0;
        foreach($arr_date as $date){
          if(!$date["isChecked"])
            break;
          $counter++;
        }
        if($counter == count($arr_date))
          $jobs_application_salary_helper->sent_all_salary($check_log->jobs_application);
      }
    }
  }
}
