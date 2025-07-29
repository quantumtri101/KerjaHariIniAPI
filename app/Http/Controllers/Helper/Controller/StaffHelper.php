<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\XPTransactionHelper;

use App\Models\Jobs;
use App\Models\JobsApprove;
use App\Models\User;

use App\Jobs\SendPushNotificationJob;

class StaffHelper extends BaseController{
  public function add_jobs_approve($user){
    if($user->is_active == 1){
      $arr_jobs = Jobs::where('company_id', '=', $user->company->id)->where('status', '=', 'open')->get();
      foreach($arr_jobs as $jobs){
        $data = JobsApprove::where('jobs1_id', '=', $jobs->id)
          ->where('user_id', '=', $user->id)
          ->first();

        if(empty($data)){
          $data = new JobsApprove();
          $data->jobs1_id = $jobs->id;
          $data->user_id = $user->id;
          $data->save();

          $jobs->is_approve = 0;
          $jobs->save();
        }
      }
    }
  }
}
