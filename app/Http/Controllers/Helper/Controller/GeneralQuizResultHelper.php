<?php
namespace App\Http\Controllers\Helper\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use QrCode;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\CommunicationHelper;
use App\Http\Controllers\Helper\PaymentHelper;
use App\Http\Controllers\Helper\Controller\XPTransactionHelper;

use App\Models\GeneralQuizAnswer;
use App\Models\User;

class GeneralQuizResultHelper extends BaseController{
  public function add_answer($arr_answer, $general_quiz_result){
    $arr_temp = GeneralQuizAnswer::where('general_quiz_result_id', '=', $general_quiz_result->id)->get();

    foreach($arr_temp as $temp)
      $temp->forceDelete();

    foreach($arr_answer as $temp){
      $general_quiz_answer = new GeneralQuizAnswer();
      $general_quiz_answer->general_quiz_result_id = $general_quiz_result->id;
      $general_quiz_answer->general_quiz_option_id = $temp["general_quiz_option"]["id"];
      $general_quiz_answer->save();
    }
  }
}
