<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\PayBillController;
use App\Http\Controllers\Helper\Controller\SalaryHelper;
use App\Http\Controllers\Helper\Controller\AuthHelper;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\Controller\JobsShiftHelper;
use App\Http\Controllers\Helper\Controller\SalaryTransactionHelper;

use App\Models\User;
use App\Models\JobsShift;
use App\Models\JobsApplication;
use App\Models\JobsApproveSalary;

class SalaryController extends BaseController{
  private $arr_header = [
    [
      "id" => "created_at",
      "column" => "salary_transaction.created_at",
      "name" => "general.date",
      "data_type" => "date",
    ],
  ];

  public function index(Request $request){
    $arr_tab = [
      [
        "id" => "list_not_requested",
        "component" => "salary.component.index_table",
        "url" => url('api/jobs/shift').'?is_approve=1&arr_type=["applicant_exist"]&is_approve_check_log=1&is_requested_salary=0&is_approved_salary=0',
      ],
      [
        "id" => "list_not_approved",
        "component" => "salary.component.index_table",
        "url" => url('api/jobs/shift').'?is_approve=1&arr_type=["applicant_exist"]&is_approve_check_log=1&is_requested_salary=1&is_approved_salary=0',
      ],
      [
        "id" => "list_approved",
        "component" => "salary.component.index_table",
        "url" => url('api/jobs/shift').'?is_approve=1&arr_type=["applicant_exist"]&is_approve_check_log=1&is_requested_salary=1&is_approved_salary=1',
      ],
    ];

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'view', !empty($request->type) && $request->type == "new" ? 'salary.index' : 'salary.index_bck', [
      'arr_tab' => $arr_tab,
    ]);
  }

  public function detail(Request $request){
    $data = JobsShift::find($request->id);
    $arr_application = JobsApplication::where('jobs1_id', '=', $data->jobs->id)->get();
    $jobs_approve_salary = JobsApproveSalary::where('jobs1_id', '=', $data->jobs->id)->where('user_id', '=', Auth::user()->id)->first();
    $allow_approve_salary = !empty($jobs_approve_salary);

    $salary_already_requested_all = true;
    foreach($arr_application as $application){
      if($application->is_approve_salary == 'not_yet_approved'){
        $salary_already_requested_all = false;
        break;
      }
    }

    // dd($data);
    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "salary.component.general_info",
      ],
      [
        "id" => "list_salary",
        "component" => "salary.component.list_salary",
      ],
      [
        "id" => "list_additional_salary",
        "component" => "salary.component.list_additional_salary",
      ],
      // [
      //   "id" => "list_check_out",
      //   "component" => "check_log.component.list_check_out",
      // ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'salary.detail', [
      'jobs_shift' => $data,
      'arr_application' => $arr_application,
      'arr_tab' => $arr_tab,
      'allow_approve_salary' => $allow_approve_salary,
      'salary_already_requested_all' => $salary_already_requested_all,
    ]);
  }

  public function edit_salary(Request $request){
    $salary_transaction_helper = new SalaryTransactionHelper();
    $salary_helper = new SalaryHelper();
    $helper = new JobsShiftHelper();
    // dd($request->all());

    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $user = User::find($request->user_id);
    $jobs_application = JobsApplication::where('jobs1_id', '=', $jobs_shift->jobs->id)->where('user_id', '=', $user->id)->first();
    $jobs_application->salary_approve = str_replace('.', '', $request->salary);
    $jobs_application->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/salary/detail?id='.$jobs_shift->id);
  }

  public function edit_additional_salary(Request $request){
    $salary_transaction_helper = new SalaryTransactionHelper();
    $helper = new JobsShiftHelper();
    $salary_helper = new SalaryHelper();
    // dd($request->all());

    $arr_image = json_decode($request->arr_image, true);
    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $jobs_application = JobsApplication::find($request->jobs_application_id);
    $jobs_application->additional_salary = str_replace('.', '', $request->additional_salary);
    $jobs_application->is_approve_additional_salary = "requested";
    $jobs_application->save();

    $salary_helper->add_additional_salary_document($arr_image, $jobs_application);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/salary/detail?id='.$jobs_shift->id);
  }

  public function change_approve_salary(Request $request){
    $salary_transaction_helper = new SalaryTransactionHelper();
    $helper = new JobsShiftHelper();
    $salary_helper = new SalaryHelper();

    $arr_image = json_decode($request->arr_image, true);
    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $user = User::find($request->user_id);
    $jobs_application = JobsApplication::where('jobs1_id', '=', $jobs_shift->jobs->id)->where('user_id', '=', $user->id)->first();
    $jobs_application->is_approve_salary = $request->is_approve_salary;
    if($jobs_application->is_approve_salary == "approved")
      $jobs_application->status = 'done';
    if(!empty($request->decline_reason))
      $jobs_application->decline_reason_salary = $request->decline_reason;
    $jobs_application->save();

    $this->communication_helper->send_push_notif($jobs_application->user, 'Status Gaji', $jobs_application->is_approve_salary == 'approved' ? 'Gaji untuk Pekerjaan '.$jobs_application->jobs->name.' telah dicairkan ke akun Anda' : 'Gaji untuk Pekerjaan '.$jobs_application->jobs->name.' telah ditolak', ["id" => $jobs_application->jobs->id, 'type' => "salary"]);
    if($jobs_application->is_approve_salary == "approved"){
      $salary_transaction_helper->add_transaction($jobs_application->user, $jobs_application->salary_approve, null, 'in', 'Salary from jobs ID '.$jobs_application->jobs->id);

      $jobs_application->salary_sent_at = Carbon::now();
      $jobs_application->save();
    }

    $helper->check_requested_check_log($jobs_shift);
    $helper->check_approve_salary($jobs_shift);
    if(!empty($arr_image))
      $salary_helper->add_salary_document($arr_image, $jobs_application);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/salary/detail?id='.$jobs_shift->id);
  }

  public function change_approve_salary_all(Request $request){
    $salary_transaction_helper = new SalaryTransactionHelper();
    $helper = new JobsShiftHelper();
    $salary_helper = new SalaryHelper();

    $arr_image = json_decode($request->arr_image, true);
    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $arr_jobs_application = JobsApplication::where('jobs1_id', '=', $jobs_shift->jobs->id)->get();
    foreach($arr_jobs_application as $jobs_application){
      $jobs_application->is_approve_salary = $request->is_approve_salary;
      if($jobs_application->is_approve_salary == "approved")
        $jobs_application->status = 'done';
      if(!empty($request->decline_reason))
        $jobs_application->decline_reason_salary = $request->decline_reason;
      $jobs_application->save();

      $this->communication_helper->send_push_notif($jobs_application->user, 'Status Gaji', $jobs_application->is_approve_salary == 'approved' ? 'Gaji anda telah dimasukkan ke saldo anda' : 'Gaji anda ditolak', ["id" => $jobs_application->jobs->id, 'type' => "salary"]);
      if($jobs_application->is_approve_salary == "approved"){
        $salary_transaction_helper->add_transaction($jobs_application->user, $jobs_application->salary_approve, null, 'in', 'Salary from jobs ID '.$jobs_application->jobs->id);

        $jobs_application->salary_sent_at = Carbon::now();
        $jobs_application->save();
      }

      if(!empty($arr_image))
        $salary_helper->add_salary_document($arr_image, $jobs_application);
    }

    $helper->check_requested_check_log($jobs_shift);
    $helper->check_approve_salary($jobs_shift);
    

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/salary/detail?id='.$jobs_shift->id);
  }

  public function change_approve_additional_salary(Request $request){
    $salary_transaction_helper = new SalaryTransactionHelper();
    $helper = new JobsShiftHelper();

    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $user = User::find($request->user_id);
    $jobs_application = JobsApplication::where('jobs1_id', '=', $jobs_shift->jobs->id)->where('user_id', '=', $user->id)->first();
    $jobs_application->is_approve_additional_salary = $request->is_approve_additional_salary;
    // if($jobs_application->is_approve_additional_salary == "approved")
    //   $jobs_application->status = 'done';
    if(!empty($request->decline_reason))
      $jobs_application->decline_reason_additional_salary = $request->decline_reason;
    $jobs_application->save();

    $this->communication_helper->send_push_notif($jobs_application->user, 'Status Gaji Tambahan', $jobs_application->is_approve_salary == 'approved' ? 'Gaji tambahan anda telah dimasukkan ke saldo anda' : 'Gaji tambahan anda ditolak', ["id" => $jobs_application->jobs->id, 'type' => "salary"]);
    if($jobs_application->is_approve_additional_salary == "approved"){
      $salary_transaction_helper->add_transaction($jobs_application->user, $jobs_application->additional_salary, null, 'in', 'Overtime Salary from jobs ID '.$jobs_application->jobs->id);

      $jobs_application->additional_salary_sent_at = Carbon::now();
      $jobs_application->save();
    }

    $helper->check_requested_check_log($jobs_shift);
    $helper->check_approve_salary($jobs_shift);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/salary/detail?id='.$jobs_shift->id);
  }
}
