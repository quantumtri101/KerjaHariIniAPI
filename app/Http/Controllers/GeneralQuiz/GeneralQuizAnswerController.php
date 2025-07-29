<?php
namespace App\Http\Controllers\GeneralQuiz;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\GeneralQuizAnswer;
use App\Models\GeneralQuizQuestion;
use App\Models\GeneralQuizOption;
use App\Models\GeneralQuizResult;

class GeneralQuizAnswerController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "general_quiz_answer.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $option_true = GeneralQuizOption::where('is_true', '=', 1)
        ->where('general_quiz_question_id', '=', $data->general_quiz_option->general_quiz_question->id)
        ->first();
      $data->option_true = $option_true->option;
      $data->answer = $data->general_quiz_option->option;
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'general_quiz.answer.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $general_quiz_answer_model = new GeneralQuizAnswer();
    $general_quiz_question_model = new GeneralQuizQuestion();
    $general_quiz_option_model = new GeneralQuizOption();
    $general_quiz_result_model = new GeneralQuizResult();

    $arr = GeneralQuizAnswer::select($general_quiz_answer_model->get_table_name().'.*', $general_quiz_option_model->get_table_name().'.option as option', $general_quiz_question_model->get_table_name().'.name as question',)
      ->join($general_quiz_option_model->get_table_name(), $general_quiz_option_model->get_table_name().'.id', '=', $general_quiz_answer_model->get_table_name().'.general_quiz_option_id')
      ->join($general_quiz_question_model->get_table_name(), $general_quiz_question_model->get_table_name().'.id', '=', $general_quiz_option_model->get_table_name().'.general_quiz_question_id');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->user_id)){
      $temp_result = GeneralQuizResult::select('user_id')
        ->selectRaw('MAX(id) as id')
        ->where('user_id', '=', $request->user_id)
        ->groupBy('user_id');

      $arr = $arr->joinSub($temp_result, $general_quiz_result_model->get_table_name(), $general_quiz_result_model->get_table_name().'.id', '=', $general_quiz_answer_model->get_table_name().'.general_quiz_result_id');
    }

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function post(Request $request){
    $data = new GeneralQuizAnswer();
    $data->general_quiz_option_id = $request->general_quiz_option_id;
    $data->general_quiz_result_id = $request->general_quiz_result_id;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/answer');
  }

  public function put(Request $request){
    $data = GeneralQuizAnswer::find($request->id);
    $data->general_quiz_option_id = $request->general_quiz_option_id;
    $data->general_quiz_result_id = $request->general_quiz_result_id;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/answer');
  }

  public function delete(Request $request){
    GeneralQuizAnswer::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/answer');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
