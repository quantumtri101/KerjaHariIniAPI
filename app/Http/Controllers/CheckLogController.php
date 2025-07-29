<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Excel;
use NEWPDF;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\CheckLogHelper;
use App\Http\Controllers\Helper\Controller\SalaryTransactionHelper;
use App\Http\Controllers\Helper\Controller\JobsShiftHelper;

use App\Models\CheckLog;
use App\Models\Jobs;
use App\Models\JobsShift;
use App\Models\JobsApplication;
use App\Models\JobsApproveCheckLog;
use App\Models\Event;
use App\Models\User;

use App\Exports\CheckLogExport;
use App\Exports\CheckLogShiftExport;

class CheckLogController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "check_log.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    $arr_tab = [
      [
        "id" => "list_not_requested",
        "component" => "check_log.component.index_table",
        "url" => url('api/jobs/shift').'?is_approve=1&arr_type=["applicant_full"]&is_requested_check_log=0&is_approve_check_log=0',
      ],
      [
        "id" => "list_not_approved",
        "component" => "check_log.component.index_table",
        "url" => url('api/jobs/shift').'?is_approve=1&arr_type=["applicant_full"]&is_requested_check_log=1&is_approve_check_log=0',
      ],
      [
        "id" => "list_approved",
        "component" => "check_log.component.index_table",
        "url" => url('api/jobs/shift').'?is_approve=1&arr_type=["applicant_full"]&is_requested_check_log=0&is_approve_check_log=1',
      ],
    ];

    foreach($arr as $data){
      $this->relationship_helper->check_log($data, $request);
    }
    // dd($arr_tab);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'check_log.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
      'arr_tab' => $arr_tab,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $check_log_model = new CheckLog();
    $jobs_application_model = new JobsApplication();
    $jobs_model = new Jobs();
    $user_model = new User();
    $event_model = new Event();

    $arr = CheckLog::select($check_log_model->get_table_name().'.*', $user_model->get_table_name().'.name as user_name', $event_model->get_table_name().'.name as event_name', )
      ->join($jobs_application_model->get_table_name(), $check_log_model->get_table_name().'.jobs_application_id', '=', $jobs_application_model->get_table_name().'.id')
      ->join($jobs_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->join($event_model->get_table_name(), $jobs_model->get_table_name().'.event_id', '=', $event_model->get_table_name().'.id')
      ->join($user_model->get_table_name(), $check_log_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($check_log_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(!empty($request->jobs_id))
      $arr = $arr->where($jobs_model->get_table_name().'.id', '=', $request->jobs_id);

    if(!empty($request->jobs_shift_id))
      $arr = $arr->where($check_log_model->get_table_name().'.jobs_shift_id', '=', $request->jobs_shift_id);

    if(!empty($request->type))
      $arr = $arr->where($check_log_model->get_table_name().'.type', '=', $request->type);

    if(!empty($request->api_type)){
      if($request->api_type == 'check_in')
        $arr = $arr->where($check_log_model->get_table_name().'.type', '=', 'check_in');
    }

    if(!empty($request->jobs_application_id))
      $arr = $arr->where($check_log_model->get_table_name().'.jobs_application_id', '=', $request->jobs_application_id);

    if(!empty($request->user_id))
      $arr = $arr->where($check_log_model->get_table_name().'.user_id', '=', $request->user_id);

    if(empty($request->id) && empty($request->user_id) && Auth::check() && Auth::user()->type->name == 'customer')
      $arr = $arr->where($check_log_model->get_table_name().'.user_id', '=', Auth::user()->id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = CheckLog::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'check_log.action', [
      'check_log' => $data,
    ]);
  }

  public function maps(Request $request){
    $data = CheckLog::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'check_log.maps', [
      'check_log' => $data,
    ]);
  }

  public function export(Request $request){
    $jobs_shift_model = new JobsShift();
    $jobs_model = new Jobs();

    $arr_jobs_shift = [];
    if(!empty(Auth::user()->company))
      $arr_jobs_shift = JobsShift::select($jobs_shift_model->get_table_name().'.*')
        ->join($jobs_model->get_table_name(), $jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id)
        ->get();
    else
      $arr_jobs_shift = JobsShift::all();
    foreach($arr_jobs_shift as $shift){
      $this->relationship_helper->jobs_shift($shift);
    }
    return Excel::download(new CheckLogExport($arr_jobs_shift), 'absensi.xlsx');
  }

  public function export_shift(Request $request){
    $jobs_shift = JobsShift::find($request->id);
    $arr_check_log = CheckLog::where('jobs_shift_id', '=', $request->id)
      ->where('type', '=', 'check_in')
      ->get();
    
    foreach($arr_check_log as $check_log){
      $check_log->check_out = CheckLog::where('jobs_application_id', '=', $check_log->jobs_application->id)
        ->where('type', '=', 'check_out')
        ->first();
    }
    
    return Excel::download(new CheckLogShiftExport($arr_check_log, $jobs_shift), 'absensi.xlsx');
  }

  public function export_shift_pdf(Request $request){
    $jobs_shift = JobsShift::find($request->id);
    $arr_check_log = CheckLog::where('jobs_shift_id', '=', $request->id)
      ->where('type', '=', 'check_in')
      ->get();
    
    foreach($arr_check_log as $check_log){
      $check_log->check_out = CheckLog::where('jobs_application_id', '=', $check_log->jobs_application->id)
        ->where('type', '=', 'check_out')
        ->first();
    }

    $dompdf = NEWPDF::loadHTML(view('exports.check_log_shift_pdf', [
      'arr_check_log' => $arr_check_log,
      'jobs_shift' => $jobs_shift,
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('check_log_shift.pdf');
  }

  public function detail(Request $request){
    $data = JobsShift::find($request->id);
    $arr_application = JobsApplication::where('jobs1_id', '=', $data->jobs->id)->get();
    $flag = true;
    foreach($arr_application as $application){
      $arr_check_log = CheckLog::where('jobs_application_id', '=', $application->id)
        ->where('jobs_shift_id', '=', $data->id)
        ->get();
      if(count($arr_check_log) < 2){
        $flag = false;
        break;
      }
    }
    $arr_check_log = CheckLog::where('jobs_shift_id', '=', $data->id)->get();
    $already_requested_all = true;
    foreach($arr_check_log as $check_log){
      if($check_log->is_approve_check_log == 'not_yet_requested'){
        $already_requested_all = false;
        break;
      }
    }
    $jobs_approve_check_log = JobsApproveCheckLog::where('jobs1_id', '=', $data->jobs->id)->where('user_id', '=', Auth::user()->id)->first();
    $allow_approve_check_log = $flag && !empty($jobs_approve_check_log);
    $allow_manual_check_log = count($arr_application) * 2 > count($arr_check_log);
    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "check_log.component.general_info",
      ],
      [
        "id" => "list_check_log",
        "component" => "check_log.component.list_check_log",
      ],
      // [
      //   "id" => "list_check_out",
      //   "component" => "check_log.component.list_check_out",
      // ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'check_log.detail', [
      'jobs_shift' => $data,
      'arr_application' => $arr_application,
      'arr_tab' => $arr_tab,
      'allow_approve_check_log' => $allow_approve_check_log,
      'allow_manual_check_log' => $allow_manual_check_log,
      'already_requested_all' => $already_requested_all,
    ]);
  }

  public function check_log_action(Request $request){
    $helper = new CheckLogHelper();
    $jobs_model = new Jobs();
    $jobs_application_model = new JobsApplication();

    // $data = explode(' ', $request->jobs_id);
    $jobs = Jobs::find($request->id);
    $user = !empty($request->user_id) ? User::find($request->user_id) : Auth::user();

    // check if jobs available
    if(empty($jobs))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'Jobs not available',
      ]);
    else if($jobs->status == 'accepted')
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'Jobs still not accepted',
      ]);

    // check distance for scan qr
    // $distance = $this->distance($jobs->latitude, $jobs->longitude, $request->latitude, $request->longitude, "K");
    // if($distance > .5)
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => 'Please move into jobs building to continue process',
    //   ]);

    // check if application available
    $jobs_application = JobsApplication::where('jobs1_id', '=', $jobs->id)
      ->where('user_id', '=', $user->id)
      ->first();
    if(empty($jobs_application))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'Jobs Application not available',
      ]);

    // check if shift available
    $jobs_shift = JobsShift::where('jobs1_id', '=', $jobs_application->jobs->id)
      ->where('end_date', '>=', Carbon::now())
      ->orderBy('start_date', 'desc')
      ->first();
    if(empty($jobs_shift))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'Shift not found',
      ]);

    // check already checked out
    $check_log = CheckLog::where('jobs_application_id', '=', $jobs_application->id)
      ->where('jobs_shift_id', '=', $jobs_shift->id)
      ->where('user_id', '=', $user->id)
      ->orderBy('date', 'desc')
      ->first();
    if(!empty($check_log) && $check_log->type == 'check_out')
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'User already checked out this shift',
      ]);

    // if(empty($check_log) && $jobs_shift->start_date->sub(2, 'hours') > Carbon::now())
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => 'Cannot check in at this time',
    //   ]);

    // prevent for 1 - 2 minutes check out after check in
    // if($check_log->type == 'check_in'){
    //   if($check_log->date_init->add(2, 'minutes') < Carbon::now())
    //     return $this->get_data_helper->return_data($request, [
    //       'status' => 'error',
    //       'message' => 'Cannot check out at this time',
    //     ]);
    // }

    // check for +2 hour start date & end date
    // if(empty($check_log) && $jobs_shift->start_date->addHours(2) < Carbon::now())
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => 'QR code expired! Your are too late to check-in',
    //   ]);
    // else if(!empty($check_log) && $jobs_shift->end_date->addHours(2) < Carbon::now())
    //   return $this->get_data_helper->return_data($request, [
    //     'status' => 'error',
    //     'message' => 'QR code expired! Your are too late to check-out',
    //   ]);

    
    
    $data = new CheckLog();
    $data->jobs_application_id = $jobs_application->id;
    $data->jobs1_id = $jobs_application->jobs->id;
    $data->jobs_shift_id = $jobs_shift->id;
    $data->user_id = $user->id;
    $data->type = empty($check_log) ? 'check_in' : 'check_out';
    $data->date = Carbon::now();
    $data->date_init = Carbon::now();
    if(isset($request->latitude))
      $data->latitude = $request->latitude;
    if(isset($request->longitude))
      $data->longitude = $request->longitude;
    $data->save();

    $user->is_working = $data->type == 'check_in' ? 1 : 0;
    $user->save();

    $jobs_application->status = $data->type == 'check_in' ? "working" : 'done';
    // $jobs_application->status = "working";
    $jobs_application->save();

    // $helper->sent_salary($data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ]);
  }

  public function post(Request $request){
    $helper = new CheckLogHelper();
    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $jobs_application = JobsApplication::where('user_id', '=', $request->user_id)
      ->where('jobs1_id', '=', $request->jobs_id)
      ->first();

    $check_log = CheckLog::where('jobs_shift_id', '=', $jobs_shift->id)
      ->where('type', '=', $request->type)
      ->where('user_id', '=', !empty($request->user_id) ? $request->user_id : Auth::user()->id)
      ->first();
    if(!empty($check_log))
      return $this->get_data_helper->return_data($request, [
        'status' => 'error',
        'message' => 'User already checked '.__('general.'.$check_log->type).' this shift',
      ], 'redirect', '/check-log/detail?id='.$jobs_shift->id);

    $data = new CheckLog();
    $data->jobs_application_id = $jobs_application->id;
    $data->jobs1_id = $jobs_application->jobs->id;
    $data->jobs_shift_id = $jobs_shift->id;
    $data->user_id = !empty($request->user_id) ? $request->user_id : Auth::user()->id;
    $data->type = $request->type;
    $data->date = !empty($request->date) ? Carbon::createFromFormat('d-m-Y H:i', $request->date) : Carbon::now();
    $data->date_init = !empty($request->date) ? Carbon::createFromFormat('d-m-Y H:i', $request->date) : Carbon::now();
    $data->latitude = $request->latitude;
    $data->longitude = $request->longitude;
    $data->save();

    $user = Auth::user();
    $user->is_working = $data->type == 'check_in' ? 1 : 0;
    $user->save();

    $jobs_application->status = "working";
    $jobs_application->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/check-log/detail?id='.$jobs_shift->id);
  }

  public function put(Request $request){
    $helper = new CheckLogHelper();
    $jobs_shift_helper = new JobsShiftHelper();

    $arr_image = json_decode($request->arr_image, true);
    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $user = User::find($request->user_id);
    $jobs_application = JobsApplication::where('jobs1_id', '=', $jobs_shift->jobs->id)->where('user_id', '=', $user->id)->first();
    
    $arr = CheckLog::where('jobs_shift_id', '=', $jobs_shift->id)
      ->where('user_id', '=', $user->id)
      ->get();
    $arr_exist = [
      "check_in" => false,
      "check_out" => false,
    ];
    foreach($arr as $data){
      // $data->is_approve_check_log = $request->is_approve;
      $data->date = Carbon::createFromFormat('d-m-Y H:i', $data->type == 'check_in' ? $request->check_in_date : $request->check_out_date);
      $data->save();
      $arr_exist[$data->type] = true;

      $helper->add_document($arr_image, $data);
    }

    foreach($arr_exist as $key => $exist){
      if(!$exist){
        $data = new CheckLog();
        $data->jobs_application_id = $jobs_application->id;
        $data->jobs1_id = $jobs_application->jobs->id;
        $data->jobs_shift_id = $jobs_shift->id;
        $data->user_id = $user->id;
        $data->type = $key;
        $data->date = Carbon::createFromFormat('d-m-Y H:i', $key == 'check_in' ? $request->check_in_date : $request->check_out_date);
        // $data->is_approve_check_log = 1;
        $data->save();

        $helper->add_document($arr_image, $data);
      }
    }
    // $jobs_shift_helper->check_approve_check_log($jobs_shift);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/check-log/detail?id='.$jobs_shift->id);
  }

  public function change_requested(Request $request){
    $helper = new CheckLogHelper();
    $jobs_shift_helper = new JobsShiftHelper();
    // dd($request->all());

    $arr_image = json_decode($request->arr_image, true);
    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $user = User::find($request->user_id);
    $jobs_application = JobsApplication::where('jobs1_id', '=', $jobs_shift->jobs->id)->where('user_id', '=', $user->id)->first();
    
    $arr = CheckLog::where('jobs_shift_id', '=', $jobs_shift->id)
      ->where('user_id', '=', $user->id)
      ->get();
    $arr_exist = [
      "check_in" => false,
      "check_out" => false,
    ];
    foreach($arr as $data){
      $data->is_approve_check_log = 'requested';
      $data->save();
      $arr_exist[$data->type] = true;

      $helper->add_document($arr_image, $data);
    }
    $jobs_shift_helper->check_requested_check_log($jobs_shift);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/check-log/detail?id='.$jobs_shift->id);
  }

  public function change_requested_all(Request $request){
    $helper = new CheckLogHelper();
    $jobs_shift_helper = new JobsShiftHelper();

    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $arr_image = json_decode($request->arr_image, true);
    $arr_user = CheckLog::select('user_id')->selectRaw('MAX(id) as id')->where('jobs_shift_id', '=', $jobs_shift->id)->groupBy('user_id')->get();
    foreach($arr_user as $user){
      $arr = CheckLog::where('jobs_shift_id', '=', $jobs_shift->id)
        ->where('user_id', '=', $user->user_id)
        ->get();
      $is_check_in_available = false;
      $is_check_out_available = false;
      foreach($arr as $data){
        if($data->type == "check_in")
          $is_check_in_available = true;
        else if($data->type == "check_out")
          $is_check_out_available = true;
      }

      if($is_check_in_available && $is_check_out_available){
        foreach($arr as $data){
          $data->is_approve_check_log = 'requested';
          $data->save();

          $helper->add_document($arr_image, $data);
        }
      }
    }
    $jobs_shift_helper->check_requested_check_log($jobs_shift);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/check-log/detail?id='.$jobs_shift->id);
  }

  public function change_approve(Request $request){
    $helper = new CheckLogHelper();
    $jobs_shift_helper = new JobsShiftHelper();

    $jobs_shift = JobsShift::find($request->jobs_shift_id);
    $user = User::find($request->user_id);
    
    $arr = CheckLog::where('jobs_shift_id', '=', $jobs_shift->id)
      ->where('user_id', '=', $user->id)
      ->get();
    $arr_exist = [
      "check_in" => false,
      "check_out" => false,
    ];
    foreach($arr as $data){
      $data->is_approve_check_log = $request->is_approve;
      if($data->is_approve_check_log == "approved")
        $data->approved_at = Carbon::now();
      else if($data->is_approve_check_log == "declined"){
        $data->decline_reason = $request->decline_reason;
        $data->declined_at = Carbon::now();
      }
      $data->save();
      $arr_exist[$data->type] = true;
    }
    $jobs_shift_helper->check_approve_check_log($jobs_shift);
    

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/check-log/detail?id='.$jobs_shift->id);
  }
  
  public function change_approve_all(Request $request){
    $helper = new CheckLogHelper();
    $jobs_shift_helper = new JobsShiftHelper();
  
    $jobs = Jobs::find($request->jobs_id);
    
    foreach($jobs->shift as $jobs_shift){
      $arr = CheckLog::where('jobs1_id', '=', $jobs_shift->id)
        ->where('user_id', '=', Auth::user()->id)
        ->get();
      $arr_exist = [
        "check_in" => false,
        "check_out" => false,
      ];
      foreach($arr as $data){
        $data->is_approve_check_log = $request->is_approve;
        if($data->is_approve_check_log == "approved")
          $data->approved_at = Carbon::now();
        else if($data->is_approve_check_log == "declined"){
          $data->decline_reason = $request->decline_reason;
          $data->declined_at = Carbon::now();
        }
        $data->save();
        $arr_exist[$data->type] = true;
      }
      $jobs_shift_helper->check_approve_check_log($jobs_shift);
    }
    
  
    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/check-log/detail?id='.$jobs_shift->id);
  }

  public function delete(Request $request){
    CheckLog::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/check-log');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
