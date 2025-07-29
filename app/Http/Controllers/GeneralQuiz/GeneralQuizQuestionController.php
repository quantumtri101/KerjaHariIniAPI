<?php
namespace App\Http\Controllers\GeneralQuiz;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\GeneralQuizQuestion;
use App\Models\GeneralQuizOption;
use App\Models\GeneralQuizAnswer;

class GeneralQuizQuestionController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "general_quiz_question.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->general_quiz_option;
      $result = GeneralQuizAnswer::where(function($where) use($data){
        foreach($data->general_quiz_option as $option)
          $where = $where->orWhere('general_quiz_option_id', '=', $option->id);
      })->first();
      $data->allow_delete = empty($result);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'general_quiz.question.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $general_quiz_question_model = new GeneralQuizQuestion();
    $general_quiz_option_model = new GeneralQuizOption();

    $arr = GeneralQuizQuestion::select($general_quiz_question_model->get_table_name().'.*')
      ->selectRaw('COUNT('.$general_quiz_option_model->get_table_name().'.id) as total_option')
      ->join($general_quiz_option_model->get_table_name(), function($join) use($general_quiz_question_model, $general_quiz_option_model) {
        $join = $join->on($general_quiz_question_model->get_table_name().'.id', '=', $general_quiz_option_model->get_table_name().'.general_quiz_question_id')
          ->whereNull($general_quiz_option_model->get_table_name().'.deleted_at');
      })
      ->groupBy('id');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function post(Request $request){
    $data = new GeneralQuizQuestion();
    $data->name = $request->name;
    $data->is_publish = $request->is_publish;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/question');
  }

  public function put(Request $request){
    $data = GeneralQuizQuestion::find($request->id);
    $data->name = $request->name;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/question');
  }

  public function delete(Request $request){
    GeneralQuizQuestion::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/question');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
