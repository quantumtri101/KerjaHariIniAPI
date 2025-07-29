<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\SalaryTransactionHelper;

use App\Models\JobsApprove;
use App\Models\User;

class JobsApproveHelper extends BaseController{
  public function edit_approve($jobs){
    $arr_user = User::where('company_id', '=', $jobs->company->id)->get();

    $arr_temp = JobsApprove::where(function($where) use($arr_user){
      foreach($arr_user as $temp){
        if(!empty($temp['id']))
          $where = $where->where('user_id','!=',$temp['id']);
      }
    })
      ->where('jobs_id', '=', $jobs->id)
      ->get();

    foreach($arr_temp as $temp)
      $temp->delete();

    foreach($arr_user as $user){
      $data = JobsApprove::where('jobs1_id', '=', $jobs->id)
        ->where('user_id', '=', $user->id)
        ->first();

      if(empty($data)){
        $data = new JobsApprove();
        $data->jobs1_id = $jobs->id;
        $data->user_id = $user->id;
        $data->save();
      }
    }
  }

  public function check_approve($jobs){
    $arr_approve = JobsApprove::where('jobs1_id', '=', $jobs->id)->get();
    $counter = 0;
    foreach($arr_approve as $approve){
      if(empty($approve->approve_at))
        break;
      $counter++;
    }

    $jobs->is_approve = $counter == count($arr_approve) ? 1 : 0;
    if(!empty($jobs->publish_start_at) && !empty($jobs->publish_end_at) && $jobs->is_approve == 1 && $jobs->publish_start_at <= Carbon::now() && $jobs->publish_end_at >= Carbon::now())
      $jobs->is_live_app = 1;
    $jobs->save();
  }
}
