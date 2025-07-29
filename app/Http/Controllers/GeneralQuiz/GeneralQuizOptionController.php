<?php
namespace App\Http\Controllers\GeneralQuiz;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\GeneralQuizOption;

class GeneralQuizOptionController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "general_quiz_option.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->status_publish = __('general.'.($data->is_publish == 1 ? 'publish' : 'not_publish'));
      $data->status_publish_format = $data->is_publish == 1 ? 'publish' : 'not_publish';
      $data->status_true = __('general.'.($data->is_true == 1 ? 'true' : 'false'));
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'general_quiz.option.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new GeneralQuizOption();

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->general_quiz_question_id))
      $arr = $arr->where('general_quiz_question_id', '=', $request->general_quiz_question_id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function post(Request $request){
    $data = new GeneralQuizOption();
    $data->general_quiz_question_id = $request->general_quiz_question_id;
    $data->option = $request->option;
    $data->is_publish = $request->is_publish;
    $data->is_true = $request->is_true;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/detail?id='.$data->general_quiz_question->id);
  }

  public function put(Request $request){
    $data = GeneralQuizOption::find($request->id);
    $data->general_quiz_question_id = $request->general_quiz_question_id;
    $data->option = $request->option;
    $data->is_publish = $request->is_publish;
    $data->is_true = $request->is_true;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/detail?id='.$data->general_quiz_question->id);
  }

  public function delete(Request $request){
    $data = GeneralQuizOption::find($request->id);
    $general_quiz_question = $data->general_quiz_question;
    $data->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/detail?id='.$general_quiz_question->id);
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
