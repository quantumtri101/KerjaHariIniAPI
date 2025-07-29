<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Info;

class TermConditionController extends BaseController{
  public function index(Request $request){
    $data = Info::where('type', 'like', 'term_condition')->first();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'view', 'master.term_condition.index', [
      'term_condition' => $data,
    ]);
  }

  public function get_view(Request $request){
    $data = Info::where('type', 'like', 'term_condition')->first();

    return $this->get_data_helper->return_data($request, [], 'view', 'other.term_condition', [
      'term_condition' => $data,
    ]);
  }

  public function put(Request $request){
    $data = Info::where('type', 'like', 'term_condition')->first();
    $data->title = $request->title;
    $data->content = $request->content;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/term-condition');
  }
}
