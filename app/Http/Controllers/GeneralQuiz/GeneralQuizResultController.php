<?php
namespace App\Http\Controllers\GeneralQuiz;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\GeneralQuizResultHelper;

use App\Models\GeneralQuizResult;
use App\Models\GeneralQuizAnswer;
use App\Models\GeneralQuizOption;
use App\Models\GeneralQuizQuestion;
use App\Models\JobsApplication;

class GeneralQuizResultController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "general_quiz_result.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'general_quiz.result.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = new GeneralQuizResult();

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(!empty($request->user_id))
      $arr = $arr->where('user_id', '=', $request->user_id);

    if(empty($request->id) && empty($request->user_id) && Auth::check() && (Auth::user()->type->name == "customer_regular" || Auth::user()->type->name == "customer_oncall"))
      $arr = $arr->where('user_id', '=', Auth::user()->id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function post(Request $request){
    $helper = new GeneralQuizResultHelper();
    
    $arr_answer = $request->arr_answer;
    $total_true = 0;
    $total_question = GeneralQuizQuestion::where('is_publish', '=', 1)->get()->count();
    foreach($arr_answer as $answer){
      $general_quiz_option = GeneralQuizOption::find($answer["general_quiz_option"]["id"]);
      $general_quiz_option_true = GeneralQuizOption::where('general_quiz_question_id', '=', $general_quiz_option->general_quiz_question->id)
        ->where('is_true', '=', 1)
        ->first();
      
      if($general_quiz_option->id == $general_quiz_option_true)
        $total_true++;
    }
    $data = GeneralQuizResult::where('user_id', '=', Auth::user()->id)->first();
    if(empty($data))
      $data = new GeneralQuizResult();

    $data->user_id = Auth::user()->id;
    $data->score = $total_true / $total_question * 100;
    $data->time_completed = $request->time_completed;
    $data->save();

    $helper->add_answer($arr_answer, $data);

    $arr_application = JobsApplication::where('user_id', '=', Auth::user()->id)->get();
    foreach($arr_application as $application){
      $application->general_quiz_result_id = $data->id;
      $application->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/result');
  }

  public function delete(Request $request){
    GeneralQuizResult::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/general-quiz/result');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
