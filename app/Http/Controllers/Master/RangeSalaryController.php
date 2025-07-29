<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\JobsRangeSalary;

class RangeSalaryController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "range_salary.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->salary_format = "Rp. ".number_format($data->min_salary, 0, ',', '.')." - ".number_format($data->max_salary, 0, ',', '.');
      $data->allow_delete = count($data->jobs_recommendation) == 0;
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.range_salary.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $range_salary_model = new JobsRangeSalary();

    $arr = JobsRangeSalary::select($range_salary_model->get_table_name().'.*', $range_salary_model->get_table_name().'.min_salary as salary_format');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = JobsRangeSalary::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'master.range_salary.action', [
      'range_salary' => $data,
    ]);
  }

  public function multiple(Request $request){
    return $this->get_data_helper->return_data($request, [], 'view', 'master.range_salary.multiple_add', []);
  }

  public function multiple_post(Request $request){
    $arr_range_salary = json_decode($request->arr_range_salary, true);

    foreach($arr_range_salary as $range_salary){
      $data = new JobsRangeSalary();
      $data->min_salary = str_replace('.', '', $range_salary['min_salary']);
      $data->max_salary = str_replace('.', '', $range_salary['max_salary']);
      $data->is_publish = $range_salary['is_publish'];
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/master/range-salary');
  }

  public function post(Request $request){
    $data = new JobsRangeSalary();
    $data->min_salary = str_replace('.', '', $request->min_salary);
    $data->max_salary = str_replace('.', '', $request->max_salary);
    $data->is_publish = $request->is_publish;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/range-salary');
  }

  public function put(Request $request){
    $data = JobsRangeSalary::find($request->id);
    $data->min_salary = str_replace('.', '', $request->min_salary);
    $data->max_salary = str_replace('.', '', $request->max_salary);
    $data->is_publish = $request->is_publish;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/range-salary');
  }

  public function delete(Request $request){
    JobsRangeSalary::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/range-salary');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
