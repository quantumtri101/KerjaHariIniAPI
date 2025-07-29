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

use App\Models\Jobs;
use App\Models\JobsImage;
use App\Models\JobsShift;
use App\Models\JobsCriteria;
use App\Models\JobsInterview;
use App\Models\JobsBriefing;
use App\Models\JobsApprove;
use App\Models\JobsQualification;
use App\Models\JobsWorkingArea;
use App\Models\JobsApproveSalary;
use App\Models\JobsApproveCheckLog;
use App\Models\JobsApplication;
use App\Models\City;
use App\Models\User;

use App\Jobs\SendPushNotificationJob;

class JobsHelper extends BaseController{
  public function edit_image($arr_image, $jobs){
    $arr_temp = JobsImage::where(function($where) use($arr_image){
      foreach($arr_image as $temp){
        if(!empty($temp['id']))
          $where = $where->where('id','!=',$temp['id']);
      }
    })
      ->where('jobs1_id', '=', $jobs->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_image as $temp){
      if(!empty($temp["id"]))
        $jobs_image = JobsImage::find($temp["id"]);
      if(empty($jobs_image))
        $jobs_image = new JobsImage();

      $jobs_image->jobs1_id = $jobs->id;
      $jobs_image->is_publish = 1;
      $jobs_image->save();

      if(!empty($temp["image"]) && $temp["image"] != ""){
        $this->file_helper->manage_image($temp["image"], $jobs_image, 'jobs');
        $jobs_image->save();
      }
      $jobs_image = null;
    }
  }

  public function edit_shift($arr_shift, $jobs){
    $arr_temp = JobsShift::where('jobs1_id', '=', $jobs->id)->get();

    foreach($arr_temp as $temp)
      $temp->forceDelete();

    foreach($arr_shift as $temp){
      $jobs_shift = new JobsShift();
      $jobs_shift->jobs1_id = $jobs->id;
      $jobs_shift->start_date = $temp["start_date"];
      $jobs_shift->end_date = $temp["end_date"];
      $jobs_shift->save();
    }
  }

  public function edit_approve($arr_approve, $jobs){
    $jobs_approve_helper = new JobsApproveHelper();
    $arr_temp = JobsApprove::where(function($where) use($arr_approve){
      foreach($arr_approve as $temp){
        if(!empty($temp['user_id']))
          $where = $where->where('user_id','!=',$temp['user_id']);
      }
    })
      ->where('jobs1_id', '=', $jobs->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_approve as $key => $temp){
      if(!empty($temp["user_id"]))
        $jobs_approve = JobsApprove::where('user_id', '=', $temp["user_id"])
          ->where('jobs1_id', '=', $jobs->id)
          ->first();
      if(empty($jobs_approve))
        $jobs_approve = new JobsApprove();

      $jobs_approve->jobs1_id = $jobs->id;
      $jobs_approve->user_id = $temp["user_id"];
      $jobs_approve->sort_order = $key + 1;
      $jobs_approve->save();
      $jobs_approve = null;
    }
    $jobs_approve_helper->check_approve($jobs);
  }

  public function edit_approve_check_log($arr_approve_check_log, $jobs){
    $arr_temp = JobsApproveCheckLog::where(function($where) use($arr_approve_check_log){
      foreach($arr_approve_check_log as $temp){
        if(!empty($temp['user_id']))
          $where = $where->where('user_id','!=',$temp['user_id']);
      }
    })
      ->where('jobs1_id', '=', $jobs->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_approve_check_log as $key => $temp){
      if(!empty($temp["user_id"]))
        $jobs_approve_check_log = JobsApproveCheckLog::where('user_id', '=', $temp["user_id"])
          ->where('jobs1_id', '=', $jobs->id)
          ->first();
      if(empty($jobs_approve_check_log))
        $jobs_approve_check_log = new JobsApproveCheckLog();

      $jobs_approve_check_log->jobs1_id = $jobs->id;
      $jobs_approve_check_log->user_id = $temp["user_id"];
      $jobs_approve_check_log->save();
      $jobs_approve_check_log = null;
    }
  }

  public function edit_approve_salary($arr_approve_salary, $jobs){
    $arr_temp = JobsApproveSalary::where(function($where) use($arr_approve_salary){
      foreach($arr_approve_salary as $temp){
        if(!empty($temp['user_id']))
          $where = $where->where('user_id','!=',$temp['user_id']);
      }
    })
      ->where('jobs1_id', '=', $jobs->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_approve_salary as $key => $temp){
      if(!empty($temp["user_id"]))
        $jobs_approve_salary = JobsApproveSalary::where('user_id', '=', $temp["user_id"])
          ->where('jobs1_id', '=', $jobs->id)
          ->first();
      if(empty($jobs_approve_salary))
        $jobs_approve_salary = new JobsApproveSalary();

      $jobs_approve_salary->jobs1_id = $jobs->id;
      $jobs_approve_salary->user_id = $temp["user_id"];
      $jobs_approve_salary->save();
      $jobs_approve_salary = null;
    }
  }

  public function edit_qualification($arr_qualification, $jobs){
    $arr_temp = JobsQualification::where(function($where) use($arr_qualification){
      foreach($arr_qualification as $temp){
        if(!empty($temp['id']))
          $where = $where->where('id','!=',$temp['id']);
      }
    })
      ->where('jobs1_id', '=', $jobs->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_qualification as $key => $temp){
      if(!empty($temp["id"]))
        $jobs_qualification = JobsQualification::find($temp["id"]);
      if(empty($jobs_qualification))
        $jobs_qualification = new JobsQualification();

      $jobs_qualification->jobs1_id = $jobs->id;
      $jobs_qualification->name = $temp["name"];
      $jobs_qualification->is_publish = $temp["is_publish"];
      $jobs_qualification->save();
      $jobs_qualification = null;
    }
  }

  public function edit_working_area($arr_working_area, $jobs){
    $arr_temp = JobsWorkingArea::where(function($where) use($arr_working_area){
      foreach($arr_working_area as $temp)
        $where = $where->where('city_id', '=', $temp);
    })
      ->where('jobs1_id', '=', $jobs->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_working_area as $key => $temp){
      $jobs_working_area = JobsWorkingArea::where('city_id', '=', $temp)
        ->where('jobs1_id', '=', $jobs->id)
        ->first();
      if(empty($jobs_working_area))
        $jobs_working_area = new JobsWorkingArea();

      $jobs_working_area->jobs1_id = $jobs->id;
      $jobs_working_area->city_id = $temp;
      $jobs_working_area->save();
      $jobs_working_area = null;
    }
  }

  public function edit_criteria($request, $jobs){
    $jobs_criteria = JobsCriteria::where('jobs1_id', '=', $jobs->id)->first();

    if(empty($jobs_criteria)){
      $jobs_criteria = new JobsCriteria();
      $jobs_criteria->jobs1_id = $jobs->id;
    }
    $jobs_criteria->education_id = $request->education_id;
    $jobs_criteria->gender = $request->gender;
    $jobs_criteria->min_age = str_replace('.', '', $request->min_age);
    $jobs_criteria->max_age = str_replace('.', '', $request->max_age);
    $jobs_criteria->has_pkwt = $request->has_pkwt;
    $jobs_criteria->has_pkhl = $request->has_pkhl;
    $jobs_criteria->is_working_same_company = $request->is_working_same_company;
    // $jobs_criteria->is_same_place = $request->is_same_place;
    $jobs_criteria->other = $request->other_criteria;
    $jobs_criteria->save();
  }

  public function edit_interview($request, $jobs){
    $jobs_interview = JobsInterview::where('jobs1_id', '=', $jobs->id)->first();

    if(empty($jobs_interview)){
      $jobs_interview = new JobsInterview();
      $jobs_interview->jobs1_id = $jobs->id;
    }
    $jobs_interview->interviewer_name = $request->interviewer_name;
    $jobs_interview->interviewer_phone = "+62".$request->interviewer_phone;
    $jobs_interview->schedule = Carbon::createFromFormat('d-m-Y H:i', $request->interview_date);
    $jobs_interview->type = $request->interview_type;
    if($jobs_interview->type == 'offline')
      $jobs_interview->location = $request->interview_location;
    else
      $jobs_interview->zoom_url = $request->interview_link;
    $jobs_interview->notes = $request->interview_notes;
    $jobs_interview->save();
  }

  public function edit_briefing($request, $jobs){
    $jobs_briefing = JobsBriefing::where('jobs1_id', '=', $jobs->id)->first();

    if(empty($jobs_briefing)){
      $jobs_briefing = new JobsBriefing();
      $jobs_briefing->jobs1_id = $jobs->id;
    }
    $jobs_briefing->pic_name = $request->pic_name;
    $jobs_briefing->pic_phone = "+62".$request->pic_phone;
    $jobs_briefing->schedule = Carbon::createFromFormat('d-m-Y H:i', $request->briefing_date);
    $jobs_briefing->location = $request->briefing_location;
    $jobs_briefing->notes = $request->briefing_notes;
    $jobs_briefing->save();
  }

  public function check_working_time($shift, $user){
    $jobs_model = new Jobs();
    $jobs_application_model = new JobsApplication();
    $jobs_shift_model = new JobsShift();

    $jobs_shift = JobsShift::select($jobs_shift_model->get_table_name().'.*')
      ->join($jobs_model->get_table_name(), $jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->join($jobs_application_model->get_table_name(), function($join) use($jobs_application_model, $jobs_model, $user) {
        $join = $join->on($jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
          ->where($jobs_application_model->get_table_name().'.user_id', '=', $user->id)
          ->where($jobs_application_model->get_table_name().'.status', '!=', 'declined')
          ->where($jobs_application_model->get_table_name().'.status', '!=', 'expired');
      })
      ->where(function($where) use($jobs_shift_model, $shift) {
        $where = $where->orWhere(function($where1) use($jobs_shift_model, $shift) {
          $where1 = $where1->whereBetween($jobs_shift_model->get_table_name().'.start_date', [$shift->start_date, $shift->end_date]);
        })->orWhere(function($where1) use($jobs_shift_model, $shift) {
          $where1 = $where1->whereBetween($jobs_shift_model->get_table_name().'.end_date', [$shift->start_date, $shift->end_date]);
        })->orWhere(function($where1) use($jobs_shift_model, $shift) {
          $where1 = $where1->where($jobs_shift_model->get_table_name().'.start_date', '<=', $shift->start_date)
            ->where($jobs_shift_model->get_table_name().'.end_date', '>=', $shift->start_date);
        })->orWhere(function($where1) use($jobs_shift_model, $shift) {
          $where1 = $where1->where($jobs_shift_model->get_table_name().'.start_date', '>=', $shift->start_date)
            ->where($jobs_shift_model->get_table_name().'.end_date', '<=', $shift->start_date);
        });
      })
      ->where($jobs_model->get_table_name().'.id', '!=', $shift->jobs->id)
      ->first();
    return $jobs_shift;
  }

  public function check_working_time_all_user($shift, $arr_user, $type = "regular"){
    foreach($arr_user as $user){
      $user1 = User::find($user["id"]);
      $jobs_shift = $this->check_working_time($shift, $user1);
      if(!empty($jobs_shift))
        return [
          'status' => 'error',
          'message' => 'Staff '.$type.' '.$user1->name.' already work at '.$jobs_shift->jobs->name.' at same time',
        ];
    }
    return null;
  }
}
