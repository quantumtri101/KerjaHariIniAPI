<?php
namespace App\Http\Controllers\GeneralQuiz;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\GeneralQuizQuestion;
use App\Models\GeneralQuizOption;
use App\Models\GeneralQuizAnswer;

class GeneralQuizController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "general_quiz.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $key => $data){
      $result = GeneralQuizAnswer::where(function($where) use($data){
        foreach($data->general_quiz_option as $option)
          $where = $where->orWhere('general_quiz_option_id', '=', $option->id);
      })->first();
      $data->allow_delete = empty($result);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'general_quiz.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new GeneralQuizQuestion();

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
      $data = GeneralQuizQuestion::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'general_quiz.action', [
      'general_quiz_question' => $data,
    ]);
  }

  public function detail(Request $request){
    $data = GeneralQuizQuestion::find($request->id);

    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "general_quiz.component.general_info",
      ],
      [
        "id" => "list_option",
        "component" => "general_quiz.component.list_option",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'general_quiz.detail', [
      'general_quiz_question' => $data,
      'arr_tab' => $arr_tab,
    ]);
  }

  public function multiple(Request $request){
    return $this->get_data_helper->return_data($request, [], 'view', 'general_quiz.multiple_add', []);
  }

  public function multiple_post(Request $request){
    $arr_general_quiz = json_decode($request->arr_general_quiz, true);

    foreach($arr_general_quiz as $general_quiz){
      $general_quiz_question = new GeneralQuizQuestion();
      $general_quiz_question->is_publish = $general_quiz['is_publish'];
      $general_quiz_question->name = $general_quiz['question'];
      $general_quiz_question->save();
      $general_quiz_question->refresh();

      foreach($general_quiz["arr_option"] as $option){
        $general_quiz_option = new GeneralQuizOption();
        $general_quiz_option->general_quiz_question_id = $general_quiz_question->id;
        $general_quiz_option->is_publish = $general_quiz['is_publish'];
        $general_quiz_option->option = $option['name'];
        $general_quiz_option->is_true = $option['is_true'] ? 1 : 0;
        $general_quiz_option->save();
      }
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz');
  }

  public function post(Request $request){
    $data = new GeneralQuizQuestion();
    $data->name = $request->name;
    $data->is_publish = $request->is_publish;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz');
  }

  public function put(Request $request){
    $data = GeneralQuizQuestion::find($request->id);
    $data->name = $request->name;
    $data->is_publish = $request->is_publish;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz');
  }

  public function delete(Request $request){
    GeneralQuizQuestion::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
