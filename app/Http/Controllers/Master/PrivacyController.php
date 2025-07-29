<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Info;

class PrivacyController extends BaseController{
  public function index(Request $request){
    $data = Info::where('type', 'like', 'privacy_policy')->first();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'view', 'master.privacy.index', [
      'privacy' => $data,
    ]);
  }

  public function get_view(Request $request){
    $data = Info::where('type', 'like', 'privacy_policy')->first();

    return $this->get_data_helper->return_data($request, [], 'view', 'other.privacy_policy', [
      'privacy_policy' => $data,
    ]);
  }

  public function put(Request $request){
    $data = Info::where('type', 'like', 'privacy_policy')->first();
    $data->title = $request->title;
    $data->content = $request->content;
    $data->save();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/privacy');
  }
}
