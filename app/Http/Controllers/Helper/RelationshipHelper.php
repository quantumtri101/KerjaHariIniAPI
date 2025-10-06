<?php
namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\Helper\Controller\ClassHelper;
use App\Http\Controllers\Helper\RelationshipHelper2;
use App\Http\Controllers\Helper\Controller\BaseHelper;

use App\Models\JobsApplication;
use App\Models\ChatRoom;
use App\Models\Jobs;
use App\Models\JobsApprove;
use App\Models\JobsShift;
use App\Models\User;
use App\Models\Type;
use App\Models\CheckLog;
use App\Models\JobsRecommendation;
use App\Models\JobsRecommendationCity;
use App\Models\JobsRecommendationSubCategory;
use App\Models\Setting;
use App\Models\MemberBranch;
use App\Models\Traffic;
use App\Models\BonusPointTransaction;

class RelationshipHelper{
  public $date_format = '%d %B %Y %H:%M';
  public $date_only_format = '%d %B %Y';
  public $date_data_format = '%Y-%m-%d %H:%M:%S';

  public function user($data, $request = null){
    $jobs_application_model = new JobsApplication();
    $jobs_model = new Jobs();
    $jobs_approve_model = new JobsApprove();
    $jobs_shift_model = new JobsShift();

    $data->birth_date_format = !empty($data->birth_date) ? $data->birth_date->formatLocalized('%d %B %Y') : '-';
    $data->resume;
    $data->general_quiz_result;
    $data->salary_balance_format = "Rp. ".number_format($data->salary_balance, 0, ',', '.');
    $data->status_active_format = __('general.'.($data->is_active == 1 ? 'active' : 'not_active'));
    $data->sub_category;
    $data->jobs_recommendation;
    $data->qr_code_id = base64_encode(QrCode::format('svg')->size(300)->generate($data->id));
    $data->single_firebase_token = count($data->firebase_token) > 0 ? $data->firebase_token[count($data->firebase_token) - 1] : null;

    foreach($data->resume as $resume){
      $resume->created_at_format = $resume->created_at->formatLocalized('%d %B %Y');
      $resume->gender_format = __('general.'.($resume->gender == 1 ? 'male' : 'female'));
      $resume->marital_status_format = __('general.'.$resume->marital_status);
      $resume->birth_date_format = $resume->birth_date->formatLocalized('%d %B %Y');
      $resume->education;
      foreach($resume->skill as $skill){
        $skill->skill;
      }
      foreach($resume->experience as $experience){
        $experience->city;
      }
    }

    if(!empty($request->jobs_id)){
      $data->jobs_application = JobsApplication::where('user_id', '=', $data->id)
        ->where('jobs1_id', '=', $request->jobs_id)
        ->first();

      $jobs_recommendation = JobsRecommendation::where('user_id', '=', $data->id)->first();
      if(!empty($jobs_recommendation) && !empty($request->jobs_id)){
        $jobs = Jobs::find($request->jobs_id);
        $min_year = Carbon::now()->subYears($jobs->criteria[0]->min_age);
        $max_year = Carbon::now()->subYears($jobs->criteria[0]->max_age);
        $range_salary = !empty($jobs_recommendation->range_salary) && $jobs->salary_casual > $jobs_recommendation->range_salary->min_salary && $jobs->salary_casual < $jobs_recommendation->range_salary->max_salary;
        $jobs_recommendation_city = JobsRecommendationCity::where('jobs_recommendation_id', '=', $jobs_recommendation->id)->where('city_id', '=', $jobs->city->id)->first();
        $jobs_recommendation_sub_category = JobsRecommendationSubCategory::where('jobs_recommendation_id', '=', $jobs_recommendation->id)->where('sub_category_id', '=', $jobs->sub_category->id)->first();
        $data->is_recommendation = $range_salary && $jobs_recommendation_city && $jobs_recommendation_sub_category;
      }
      else
        $data->is_recommendation = false;
    }
    if(!empty($data->company_position))
      $data->company_position_name = $data->company_position->name;
    if(!empty($data->company))
      $data->company_name = $data->company->name;

    if($data->type->name == "customer_oncall"){
      $jobs_application = JobsApplication::select($jobs_application_model->get_table_name().'.*', $jobs_shift_model->get_table_name().'.id as jobs_shift_id')
        ->join($jobs_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->join($jobs_shift_model->get_table_name(), function($join) use($jobs_shift_model, $jobs_model){
          $join = $join->on($jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
            ->where($jobs_shift_model->get_table_name().'.start_date', 'like', Carbon::now()->formatLocalized('%Y-%m-%d').'%');
        })
        ->where($jobs_application_model->get_table_name().'.user_id', '=', $data->id)
        ->where(function($where) use($jobs_application_model) {
          $where = $where->orWhere($jobs_application_model->get_table_name().'.status', '=', 'accepted')
            ->orWhere($jobs_application_model->get_table_name().'.status', '=', 'working');
        })
        ->first();
      if(!empty($jobs_application))
        $check_log = CheckLog::where('jobs_application_id', '=', $jobs_application->id)
          ->where('jobs_shift_id', '=', $jobs_application->jobs_shift_id)
          ->orderBy('date', 'desc')
          ->first();

      $data->available_to_check_in = !empty($jobs_application) && empty($check_log);
      $data->available_to_check_out = !empty($jobs_application) && !empty($check_log) && $check_log->type == "check_in";
    }

    if($data->type->name == "customer_regular" || $data->type->name == "customer_oncall"){
      $data->allow_delete = $data->is_working == 0;
    }
    else if($data->type->name == "RO"){
      $jobs = Jobs::where('created_by', '=', $data->id)->first();
      $data->allow_delete = empty($jobs);
    }
    else if($data->type->name == "staff"){
      $approve = JobsApprove::select($jobs_approve_model->get_table_name().'.*')
        ->join($jobs_model->get_table_name(), $jobs_approve_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->where($jobs_approve_model->get_table_name().'.user_id', '=', $data->id)
        ->where(function($where) use($jobs_model) {
          $where = $where->orWhere($jobs_model->get_table_name().'.status', '=', 'open')
            ->orWhere($jobs_model->get_table_name().'.status', '=', 'accepted');
        })
        ->first();
      $data->allow_delete = empty($approve);
    }
  }

  public function salary_transaction($data){
    $data->created_at_format = $data->created_at->formatLocalized('%d %B %Y %H:%M');
    $data->updated_at_format = $data->updated_at->formatLocalized('%d %B %Y %H:%M');
    $data->date_format = $data->date->formatLocalized('%d %B %Y %H:%M');
    $data->date_format2 = $data->date->formatLocalized('%Y-%m-%d %H:%M:%S');
    $data->amount_format = ($data->type == 'in' ? '+' : '-')." Rp. ".number_format($data->amount, 0, ',', '.');
    $data->status_approve_format = __('general.'.($data->is_approve ? 'approve' : 'not_approve'));
  }

  public function request_withdraw($data){
    $data->created_at_format = $data->created_at->formatLocalized('%d %B %Y %H:%M');
    $data->updated_at_format = $data->updated_at->formatLocalized('%d %B %Y %H:%M');
    $data->date_format = $data->date->formatLocalized('%d %B %Y %H:%M');
    $data->date_format2 = $data->date->formatLocalized('%Y-%m-%d %H:%M:%S');
    $data->total_amount_format = " Rp. ".number_format($data->total_amount, 0, ',', '.');
    $data->status_approve_format = '<span class="bg-'.($data->status == 'accepted' ? 'success' : ($data->status == 'requested' ? 'warning' : 'danger')).' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.__('general.'.$data->status).'</span>';
  }

  public function event($data){
    $jobs_application_model = new JobsApplication();
    $jobs_model = new Jobs();
    $user_model = new User();
    $type_model = new Type();

    $data->date_format = $data->start_date->formatLocalized('%d %B %Y %H:%M').' - '.$data->end_date->formatLocalized('%d %B %Y %H:%M');
    $data->created_at_format = $data->created_at->formatLocalized('%d %B %Y %H:%M');
    $data->updated_at_format = $data->updated_at->formatLocalized('%d %B %Y %H:%M');
    $data->allow_delete = count($data->jobs) == 0;
		$data->company;
		$data->image;

    $data->total_staff_regular = JobsApplication::selectRaw('COUNT('.$jobs_application_model->get_table_name().'.id) as total')
      ->join($user_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->where(function($where) use($data, $jobs_application_model){
        foreach($data->jobs as $jobs)
          $where = $where->where($jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs->id);
      })
      ->where($type_model->get_table_name().'.name', '=', 'customer_regular')
      ->first()->total;
    $data->total_staff_oncall = JobsApplication::selectRaw('COUNT('.$jobs_application_model->get_table_name().'.id) as total')
      ->join($user_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->where(function($where) use($data, $jobs_application_model){
        foreach($data->jobs as $jobs)
          $where = $where->where($jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs->id);
      })
      ->where($type_model->get_table_name().'.name', '=', 'customer_oncall')
      ->first()->total;
    $data->total_jobs = Jobs::selectRaw('COUNT('.$jobs_model->get_table_name().'.id) as total')
      ->where(function($where) use($data, $jobs_model){
        foreach($data->jobs as $jobs)
          $where = $where->where($jobs_model->get_table_name().'.id', '=', $jobs->id);
      })
      ->first()->total;
    $arr_jobs = Jobs::where(function($where) use($data, $jobs_model){
        foreach($data->jobs as $jobs)
          $where = $where->where($jobs_model->get_table_name().'.id', '=', $jobs->id);
      })
      ->get();

    $total_budget = 0;
    foreach($arr_jobs as $jobs){
      if($jobs->salary_type_casual == "fixed")
        $total_budget += $jobs->salary_casual * $jobs->num_people_required;
      else if($jobs->salary_type_casual == "per_hour"){
        $total_hour = 0;
        foreach($jobs->shift as $shift)
          $total_hour += $shift->end_date->diffInHours($shift->start_date);
        $total_budget += $jobs->salary_casual * $total_hour * $jobs->num_people_required;
      }

    }
    $data->total_budget = $total_budget;

    $total_expense = 0;
    foreach($arr_jobs as $jobs){
      foreach($jobs->application as $application){
        if($application->status == "done")
          $total_expense += $application->salary_approve + $application->additional_salary;
      }
    }
    $data->total_expense = $total_expense;

    foreach($data->jobs as $jobs){
      $jobs->start_shift = JobsShift::where('jobs1_id', '=', $jobs->id)->orderBy('start_date', 'asc')->first();
      $jobs->end_shift = JobsShift::where('jobs1_id', '=', $jobs->id)->orderBy('end_date', 'desc')->first();
    }
  }

  public function chat($data){
    $data->person_1;
    $data->person_2;
    $data->order;
    $chat_room = ChatRoom::where('chat_id', '=', $data->id)
      ->orderBy('created_at', 'desc')
      ->first();
    $data->last_message = !empty($chat_room) ? (!empty($chat_room->message) ? $chat_room->message : 'Image') : '-';
    $data->total_unread = ChatRoom::where('chat_id', '=', $data->id)
      ->where('receiver_id', '=', Auth::user()->id)
      ->whereNull('read_at')
      ->get()
      ->count();
    $data->receiver = Auth::user()->id == $data->person_1_id ? $data->person_2 : $data->person_1;
  }

  public function resume($data){
    $data->city;
    $data->bank;
    $data->id_no = $data->user->id_no;
    $data->education;
    foreach($data->experience as $experience)
      $experience->city->province->country;
    $data->jobs_application;
    foreach($data->skill as $skill){
      $skill->skill;
      if(!empty($skill->skill))
        $skill->name = $skill->skill->name;
    }
  }

  public function jobs_application($data, $request = null){
    $bg_color = '';
    if($data->status == 'wait')
      $bg_color = "F5AF19";
    else if($data->status == 'interview')
      $bg_color = "FF7648";
    else if($data->status == 'accepted')
      $bg_color = "24AB70";
    else if($data->status == 'working')
      $bg_color = "228CF7";
    else if($data->status == 'declined')
      $bg_color = "FF4848";
    else if($data->status == 'done')
      $bg_color = "344ED1";

    $data->jobs->criteria;
    $data->general_quiz_score = count($data->user->general_quiz_result) > 0 ? $data->user->general_quiz_result[0]->score : 0;
    $data->user_name = $data->user->name;
    $data->text = $data->user->name;
    $data->salary_document;
    $data->additional_salary_document;
		$data->jobs->image;

    foreach($data->jobs->shift as $jobs_shift){
      $arr_check_log = CheckLog::where('jobs_application_id', '=', $data->id)
        ->where('jobs_shift_id', '=', $jobs_shift->id)
        ->orderBy('date', 'asc')
        ->get();
      $arr = [];
      foreach($arr_check_log as $check_log){
        if((Auth::user()->type->name == "customer_oncall" || Auth::user()->type->name == "customer_regular") && $check_log->user->id == Auth::user()->id)
          array_push($arr, $check_log);
        else if(Auth::user()->type->name != "customer_oncall" && Auth::user()->type->name != "customer_regular")
          array_push($arr, $check_log);
      }
      $jobs_shift->arr_check_log = $arr;
    }

    $arr = [];
    $jobs_shift = JobsShift::where('jobs1_id', '=', $data->jobs->id)
      ->where('start_date', '<=', Carbon::now())
      ->where('end_date', '>=', Carbon::now())
      ->first();
    if(!empty($jobs_shift)){
      $arr_check_log = CheckLog::where('jobs_application_id', '=', $data->id)
        ->where('jobs_shift_id', '=', $jobs_shift->id)
        ->orderBy('date', 'asc')
        ->get();
      foreach($arr_check_log as $check_log){
        if((Auth::user()->type->name == "customer_oncall" || Auth::user()->type->name == "customer_regular") && $check_log->user->id == Auth::user()->id)
          array_push($arr, $check_log);
        else if(Auth::user()->type->name != "customer_oncall" && Auth::user()->type->name != "customer_regular")
          array_push($arr, $check_log);
      }
    }

    $is_available_check_in = false;
    $is_available_check_out = false;
    if(!empty($jobs_shift)){
      if(count($arr) > 0){
        if((Auth::user()->type->name == "customer_oncall" || Auth::user()->type->name == "customer_regular") && $arr[count($arr) - 1]->type == "check_in")
          $is_available_check_out = true;
      }
      else
        $is_available_check_in = true;
    }

    $data->arr_check_log = $arr;
    $data->is_available_check_in = $is_available_check_in;
    $data->is_available_check_out = $is_available_check_out;

    $data->type_name_format = __('general.'.$data->user->type->name);
    $data->status_salary_approve_format = __('general.'.$data->is_approve_salary);
    $data->status_additional_salary_approve_format = __('general.'.$data->is_approve_additional_salary);
    $data->gender_format = __('general.'.($data->user->gender == 1 ? 'male' : 'female'));
    $data->type_name = __('general.'.$data->user->type->name);
    $data->birth_date_format = count($data->user->resume) > 0 ? $data->user->resume[0]->birth_date->formatLocalized('%d %B %Y') : '-';
    $data->created_at_format = $data->created_at->formatLocalized('%d %B %Y');
    $data->status_format = '<span class="pd-y-3 pd-x-10 tx-white tx-11 tx-roboto" style="background-color: #'.$bg_color.'">'.__('general.'.($data->status == "wait" && $data->is_approve_worker == 0 ? 'wait_customer' : $data->status)).'</span>';
    $data->allow_approve_salary = $data->is_approve_salary == "not_yet_approved" || $data->is_approve_additional_salary == "not_yet_approved";
    $data->status_bg_color = $bg_color;
    $data->generated_from_format = __('general.'.$data->generated_from);
    $data->first_question = !empty($data->first_question) ? $data->first_question : __('general.no_question');
    $data->salary_approve_format = "Rp. " . number_format($data->salary_approve, 0, ',', '.');
    $data->additional_salary_format = "Rp. " . number_format($data->additional_salary, 0, ',', '.');
  }

  public function jobs_shift($data, $key = 0){
    $data->name = __('general.num_shift', ["num" => $key + 1]);
    $data->start_date_format = $data->start_date->formatLocalized('%d %B %Y %H:%M');
    $data->end_date_format = $data->end_date->formatLocalized('%d %B %Y %H:%M');

    $data->sub_category_name = $data->jobs->sub_category->name;
    $data->jobs_name = $data->jobs->name;
    $data->working_date_format = $data->start_date->formatLocalized('%d/%m/%Y %H:%M').' - '.$data->end_date->formatLocalized('%d/%m/%Y %H:%M');
    $total_applicant = JobsApplication::where('jobs1_id', '=', $data->jobs->id)->get()->count();
    $total_check_in = CheckLog::where('jobs_shift_id', '=', $data->id)->where('type', '=', 'check_in')->get()->count();
    $total_check_out = CheckLog::where('jobs_shift_id', '=', $data->id)->where('type', '=', 'check_out')->get()->count();
    $data->total_check_in = $total_check_in;
    $data->total_check_out = $total_check_out;
    $data->total_applicant = $total_applicant;
    $data->total_check_in_format = '<span class="bg-'.($total_check_in != $total_applicant ? 'secondary' : 'success').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.$total_check_in.' / '.$total_applicant.'</span>';
    $data->total_check_out_format = '<span class="bg-'.($total_check_out != $total_applicant ? 'secondary' : 'success').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.$total_check_out.' / '.$total_applicant.'</span>';
  }

  public function jobs_approve($data){
    $data->status_approve = '<span class="bg-'.($data->status_approve == 'approved' ? 'success' : 'danger').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.__('general.'.$data->status_approve).'</span>';
    $data->approve_at_format = !empty($data->approve_at) ? $data->approve_at->formatLocalized('%d %B %Y %H:%M') : '-';
    $data->updated_at_format = !empty($data->updated_at) ? $data->updated_at->formatLocalized('%d %B %Y %H:%M') : '-';

    $document_str = '';
    foreach($data->document as $key => $document)
      $document_str .= $document->file_name.($key < count($data->document) - 1 ? ', ' : '');
    $data->document_str_format = $document_str;
  }

  public function check_log($data, $request = null){
    $data->type_format = __('general.'.$data->type);
    $data->date_format = $data->date->formatLocalized('%d %B %Y %H:%M');
    $data->user_name = $data->user->name;
    $data->type_name = __('general.'.$data->user->type->name);
    $data->is_approve_check_log_format = __('general.'.$data->is_approve_check_log);
    $data->gender = count($data->user->resume) > 0 ? __('general.'.($data->user->resume[0]->gender == 1 ? 'male' : 'female')) : '-';
    $data->id_no = count($data->user->resume) > 0 ? $data->user->resume[0]->id_no : '-';
    $data->document;

    if(!empty($request->api_type) && $request->api_type == "check_in"){
      $check_out = CheckLog::where('jobs_application_id', '=', $data->jobs_application->id)
        ->where('user_id', '=', $data->user->id)
        ->where('type', '=', 'check_out')
        ->first();

      $data->check_in_at = $data->date->formatLocalized('%H:%M');
      $data->check_out_at = !empty($check_out) ? $check_out->date->formatLocalized('%H:%M') : '-';

      $data->check_in_at_format = $data->date->formatLocalized('%d %B %Y %H:%M');
      $data->check_out_at_format = !empty($check_out) ? '<div class="position-relative"><div class="check-out-system d-inline-block mr-1"><div class="position-absolute check-out-system-info" style="bottom: 2rem; left: -2rem; width: 5rem; background-color: white; z-index: 100;">Check Out from System</div><i class="fa-solid fa-triangle-exclamation" style="color: red;"></i></div>'.$check_out->date->formatLocalized('%d %B %Y %H:%M').'</div>' : '-';

      $data->check_in2_at_format = $data->date->formatLocalized('%d-%m-%Y %H:%M');
      $data->check_out2_at_format = !empty($check_out) ? $check_out->date->formatLocalized('%d-%m-%Y %H:%M') : '';
    }
  }

  public function jobs($data, $request = null){
    $jobs_application_model = new JobsApplication();
    $jobs_model = new Jobs();
    $user_model = new User();
    $type_model = new Type();

    $jobs_shift = JobsShift::where('jobs1_id', '=', $data->id)->where('end_date', '>', Carbon::now())->first();
    $data->is_available_shift = !empty($jobs_shift);

    $data->status_publish = '<span class="bg-'.($data->is_publish == 1 ? 'success' : 'danger').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.__('general.'.($data->is_publish == 1 ? 'publish' : 'not_publish')).'</span>';
    $data->status_approve = __('general.'.($data->is_approve == 1 ? 'approve' : 'not_approve'));
    $data->status_urgent = __('general.'.($data->is_urgent == 1 ? 'urgent' : 'not_urgent'));
    $data->status_on_app = '<span class="bg-'.($data->is_live_app == 1 && $data->is_available_shift ? 'success' : 'danger').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.__('general.'.($data->is_available_shift ? ($data->is_live_app == 1 ? 'live' : 'not_live') : 'ended')).'</span>';
    $jobs_shift = JobsShift::where('jobs1_id', '=', $data->id)->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->first();
    $data->status_work = '<span class="bg-'.(!empty($jobs_shift) && $data->is_available_shift ? 'success' : 'danger').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.__('general.'.($data->is_available_shift ? (!empty($jobs_shift) ? 'on_going' : 'not_working') : 'ended')).'</span>';
    $data->status_format = __('general.'.$data->staff_type);
    $data->num_people_required_format = __('general.num_person', ['num' => number_format($data->num_people_required, 0, ',', '.')]);
    $data->salary_regular_format = "Rp. " . number_format($data->salary_regular, 0, ',', '.') . ($data->salary_type_regular == "per_hour" ? " / Hour" : "");
    $data->salary_casual_format = "Rp. " . number_format($data->salary_casual, 0, ',', '.') . ($data->salary_type_casual == "per_hour" ? " / Hour" : "");




    $jobs_application = JobsApplication::select($jobs_application_model->get_table_name().'.*')
      ->join($jobs_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
      ->join($user_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ->where($jobs_model->get_table_name().'.id', '=', $data->id)
      ->where($jobs_application_model->get_table_name().'.status', '!=', 'declined')
      ->where($jobs_application_model->get_table_name().'.status', '!=', 'expired');
    $data->application_online = $jobs_application->get();
    $jobs_application = $jobs_application->get()->count();
    $data->num_staff = '<span class="bg-'.($jobs_application == $data->num_people_required ? 'success' : 'danger').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.$jobs_application.' / '.$data->num_people_required.'</span>';
    $total_approve_check_log = 0;
    $total_approve_salary = 0;
    foreach($data->shift as $key => $shift){
      $shift->start_date_format = $shift->start_date->formatLocalized($this->date_data_format);
      $shift->end_date_format = $shift->end_date->formatLocalized($this->date_data_format);
      $shift->arr_check_log = CheckLog::where('jobs_shift_id', '=', $shift->id)->get();
      if($shift->is_approve_check_log == 1)
        $total_approve_check_log++;
      if($shift->is_approve_salary == 1)
        $total_approve_salary++;
    }
    $data->is_approve_check_log = $total_approve_check_log == count($data->shift);
    $data->is_approve_salary = $total_approve_salary == count($data->shift);
    $data->shift_start_date_format = Carbon::parse($data->shift_start_date)->formatLocalized($this->date_only_format);
    $data->shift_end_date_format = Carbon::parse($data->shift_end_date)->formatLocalized($this->date_only_format);
    $data->qualification;
    $data->sub_category->category;
    $data->company;
    $data->company->image_url = url('/image/company?file_name='.$data->company->file_name);
    $data->event;
    $data->city_name = $data->city->name;
    $data->salary_format = "Rp. ".number_format($data->salary_casual, 0, ',', '.');
    $arr_application = [];
    $arr_application_temp = JobsApplication::where('jobs1_id', '=', $data->id)->get();
    foreach($arr_application_temp as $application){
      if(Auth::check() && Auth::user()->type->name == "customer_oncall"){
        if($application->user->id == Auth::user()->id)
          array_push($arr_application, $application);
      }
      else
        array_push($arr_application, $application);
    }
    foreach($arr_application as $application){
      if(!empty($application->user->company))
        $application->user_type = "regular";
      else if(empty($application->user->company)){
        $jobs_recommendation = JobsRecommendation::where('user_id', '=', $application->user->id)->first();
        if(!empty($jobs_recommendation)){
          $total_hour = 0;
          foreach($data->shift as $shift)
            $total_hour += $shift->end_date->diffInHours($shift->start_date);
          $casual_salary = $data->salary_type_casual == "fixed" ? $data->salary_casual : $data->salary_casual * $total_hour;

          $includeCity = false;
          foreach($jobs_recommendation->city as $city){
            if($data->city->id == $city->city->id){
              $includeCity = true;
              break;
            }
          }

          $includeSubCategory = false;
          foreach($jobs_recommendation->sub_category as $sub_category){
            if($data->sub_category->id == $sub_category->sub_category->id){
              $includeSubCategory = true;
              break;
            }
          }

          if(!empty($jobs_recommendation->range_salary) && $jobs_recommendation->range_salary->min_salary <= $casual_salary && $jobs_recommendation->range_salary->max_salary >= $casual_salary && $includeCity && $includeSubCategory)
            $application->user_type = "casual";
          else
            $application->user_type = "all";
        }
        else
          $application->user_type = null;
      }
    }
    $data->application = $arr_application;
    foreach($data->image as $image)
      $image->image_url = url('/image/jobs?file_name='.$image->file_name);
    $arr_check_log = [];
    $arr_check_log_temp = CheckLog::where('jobs1_id', '=', $data->id)->orderBy('date', 'asc')->orderBy('id', 'asc')->get();
    foreach($arr_check_log_temp as $check_log){
      if(Auth::check() && Auth::user()->type->name == "customer_oncall"){
        if($check_log->user->id == Auth::user()->id)
          array_push($arr_check_log, $check_log);
      }
      else
        array_push($arr_check_log, $check_log);
    }
    $data->check_log = $arr_check_log;
    $data->interview;
    $data->briefing;
    foreach($data->criteria as $criteria)
      $criteria->education;
    $data->document;
    $data->approve;
    $data->approve_check_log;
    $data->approve_salary;
    foreach($data->working_area as $working_area)
      $working_area->city->province->country;

    if(Auth::user()->type->name == "staff"){
      $jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
        ->where('user_id', '=', Auth::user()->id)
        ->first();

      // $data->status_approve = __('general.'.$jobs_approve->status_approve);
      $data->status_approve = '<span class="bg-'.($data->is_available_shift && $jobs_approve->status_approve == 'approved' ? 'success' : 'danger').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.__('general.'.($data->is_available_shift ? ($jobs_approve->status_approve == 'approved' ? 'approve' : 'not_approve') : 'ended')).'</span>';
    }
    else if(Auth::user()->type->name == "RO" || Auth::user()->type->name == "admin"){
      $first_jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
        ->where('status_approve', '=', 'declined')
        ->where('sort_order', '=', 1)
        ->first();
      $total_jobs_accepted = JobsApprove::where('jobs1_id', '=', $data->id)
        ->where('status_approve', '=', 'approved')
        ->get()
        ->count();
      if($data->is_approve == 0 && $total_jobs_accepted == count($data->approve))
        $data->is_approve = 0;
      else if($data->is_approve == 1 && $total_jobs_accepted == count($data->approve))
        $data->is_approve = 1;
      else if(!empty($first_jobs_approve))
        $data->is_approve = -2;
      else if(empty($first_jobs_approve))
        $data->is_approve = -1;



      $data->status_approve = '<span class="bg-'.(!$data->is_available_shift || $data->is_approve == -1 || $data->is_approve == -2 ? 'danger' : 'success').' pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">'.__('general.'.($data->is_available_shift ? ($data->is_approve == 1 ? 'approve' : ($data->is_approve == -1 ? 'staff_not_approve' : ($data->is_approve == -2 ? 'staff_declined' : 'staff_approve'))) : 'ended')).'</span>';
    }


		if(Auth::user()->type->name == "staff"){
			$jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
				->where('user_id', '=', Auth::user()->id)
				->first();

			$data->status_approve_data = !empty($jobs_approve) ? $jobs_approve->status_approve : 'not_available';

      if(!empty($jobs_approve))
        $before_jobs_approve = JobsApprove::where('jobs1_id', '=', $data->id)
          ->where('sort_order', '=', $jobs_approve->sort_order - 1)
          ->first();
			$data->before_allow_edit = empty($before_jobs_approve) || (!empty($before_jobs_approve) && $before_jobs_approve->status_approve == "approved");
		}
  }
}
