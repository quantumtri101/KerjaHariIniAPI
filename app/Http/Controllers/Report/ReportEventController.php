<?php
namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;

use App\Models\Event;
use App\Models\Company;
use App\Models\JobsApplication;
use App\Models\JobsShift;
use App\Models\Jobs;
use App\Models\User;
use App\Models\Type;

class ReportEventController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "bank.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function event(Request $request){
    // $arr = Event::all();
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->total_expense = $data->total_expense_format;
      $data->total_budget = $data->total_budget_format;
      $data->total_expense_format = "Rp. ".number_format($data->total_expense_format, 0, ',', '.');
      $data->total_budget_format = "Rp. ".number_format($data->total_budget_format, 0, ',', '.');
      // $this->relationship_helper->event($data);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'report.event.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $event_model = new Event();
    $company_model = new Company();
    $jobs_model = new Jobs();
    $jobs_application_model = new JobsApplication();
    $jobs_shift_model = new JobsShift();
    $user_model = new User();
    $type_model = new Type();

    $temp = Jobs::select($jobs_model->get_table_name().'.event_id')
      ->selectRaw('MAX('.$jobs_model->get_table_name().'.id) as id')
      ->selectRaw('COUNT(IF('.$type_model->get_table_name().'.name = "customer_regular", '.$jobs_application_model->get_table_name().'.id, null)) as total_applicant_regular')
      ->selectRaw('COUNT(IF('.$type_model->get_table_name().'.name = "customer_oncall", '.$jobs_application_model->get_table_name().'.id, null)) as total_applicant_oncall')
      ->selectRaw('SUM(IF('.$jobs_application_model->get_table_name().'.salary_sent_at IS NOT NULL, '.$jobs_application_model->get_table_name().'.salary_approve, 0) + IF('.$jobs_application_model->get_table_name().'.additional_salary_sent_at IS NOT NULL, '.$jobs_application_model->get_table_name().'.additional_salary, 0)) as total_expense')
      ->groupBy('event_id')
      ->join($jobs_application_model->get_table_name(), function($join) use($jobs_application_model, $jobs_model) {
        $join = $join->on($jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
          ->whereNull($jobs_application_model->get_table_name().'.deleted_at')
          ->where($jobs_application_model->get_table_name().'.status', '!=', 'canceled');
      })
      ->join($user_model->get_table_name(), $jobs_application_model->get_table_name().'.user_id', '=', $user_model->get_table_name().'.id')
      ->join($type_model->get_table_name(), $user_model->get_table_name().'.type_id', '=', $type_model->get_table_name().'.id')
      ;
    

    $temp1 = Jobs::select($jobs_model->get_table_name().'.*', 'temp.total_applicant_regular', 'temp.total_applicant_oncall', 'temp.total_expense',)
      ->selectRaw('SUM(IF('.$jobs_model->get_table_name().'.salary_type_casual = "per_hour", TIMESTAMPDIFF(HOUR, '.$jobs_shift_model->get_table_name().'.start_date, '.$jobs_shift_model->get_table_name().'.end_date) * '.$jobs_model->get_table_name().'.salary_casual * '.$jobs_model->get_table_name().'.num_people_required, '.$jobs_model->get_table_name().'.salary_casual * '.$jobs_model->get_table_name().'.num_people_required)) as total_budget')
      
      ->joinSub($temp, 'temp', $jobs_model->get_table_name().'.id', 'temp.id')
      ->join($jobs_shift_model->get_table_name(), function($join) use($jobs_shift_model, $jobs_model) {
        $join = $join->on($jobs_shift_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
          ->whereNull($jobs_shift_model->get_table_name().'.deleted_at');
      })
      ->groupBy('id', 'total_applicant_regular', 'total_applicant_oncall', 'total_expense');
    

    $arr = Event::select($event_model->get_table_name().'.*', $event_model->get_table_name().'.start_date as date_format', $company_model->get_table_name().'.name as company_name', $jobs_model->get_table_name().'.total_applicant_regular', $jobs_model->get_table_name().'.total_applicant_oncall', $jobs_model->get_table_name().'.total_budget as total_budget_format', $jobs_model->get_table_name().'.total_expense as total_expense_format',)
      ->selectRaw('COUNT(jobs2.id) as total_jobs')
      ->leftJoin($company_model->get_table_name(), $event_model->get_table_name().'.company_id', '=', $company_model->get_table_name().'.id')
      ->joinSub($temp1, $jobs_model->get_table_name(), $jobs_model->get_table_name().'.event_id', $event_model->get_table_name().'.id')
      ->join($jobs_model->get_table_name().' as jobs2', 'jobs2.event_id', $event_model->get_table_name().'.id')
      ->groupBy('id', 'total_applicant_regular', 'total_applicant_oncall', 'total_budget', 'total_expense',);

    if(!empty($request->id))
      $arr = $arr->where($event_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where($event_model->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(empty($request->id) && Auth::check() && !empty(Auth::user()->company))
      $arr = $arr->where($event_model->get_table_name().'.company_id', '=', Auth::user()->company->id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function monthly(Request $request){
    $arr_month = $this->get_monthly_data();

    return $this->get_data_helper->return_data($request, [], 'view', 'report.monthly.index', [
      'arr_month' => $arr_month,
    ]);
  }

  private function get_monthly_data(){
    $jobs_model = new Jobs();
    $jobs_application_model = new JobsApplication();

    $start_date = Carbon::now()->subMonths(6)->startOfMonth();
    $counter_date = $start_date;
    $arr_month = [];

    while($counter_date <= Carbon::now()){
      $arr_month1 = [
        "date" => $counter_date->formatLocalized('%Y-%m'),
        "date_text" => $counter_date->formatLocalized('%B %Y'),
      ];
      $total_expense = 0;
      $arr_application = JobsApplication::select($jobs_application_model->get_table_name().'.*')
        ->join($jobs_model->get_table_name(), $jobs_application_model->get_table_name().'.jobs1_id', '=', $jobs_model->get_table_name().'.id')
        ->where(function($where) use($jobs_application_model, $counter_date) {
          $where = $where->orWhere($jobs_application_model->get_table_name().'.salary_sent_at', 'like', $counter_date->formatLocalized('%Y-%m').'%')
            ->orWhere($jobs_application_model->get_table_name().'.additional_salary_sent_at', 'like', $counter_date->formatLocalized('%Y-%m').'%');
        });

      if(Auth::check() && !empty(Auth::user()->company))
        $arr_application = $arr_application->where($jobs_model->get_table_name().'.company_id', '=', Auth::user()->company->id);

      $arr_application = $arr_application->get();

      foreach($arr_application as $application){
        if(!empty($application->salary_sent_at))
          $total_expense += $application->salary_approve;
        if(!empty($application->additional_salary_sent_at))
          $total_expense += $application->additional_salary;
      }
      $arr_month1["total_expense"] = $total_expense;
      array_push($arr_month, $arr_month1);

      $counter_date->addMonths(1);
    }

    return $arr_month;
  }

  public function export_event(Request $request){
    $arr = $this->manage_where($request, 'all');

    foreach($arr as $data){
      $data->total_expense = $data->total_expense_format;
      $data->total_budget = $data->total_budget_format;
      $data->total_expense_format = "Rp. ".number_format($data->total_expense_format, 0, ',', '.');
      $data->total_budget_format = "Rp. ".number_format($data->total_budget_format, 0, ',', '.');
      // $this->relationship_helper->event($data);
    }

    return Excel::download(new ReportEventExport($arr), 'report_event.xlsx');
  }

  public function export_monthly(Request $request){
    $arr_month = $this->get_monthly_data();

    return Excel::download(new ReportMonthlyExport($arr_month), 'report_monthly.xlsx');
  }
}
