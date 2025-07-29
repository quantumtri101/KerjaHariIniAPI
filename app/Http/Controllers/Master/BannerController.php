<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Banner;

class BannerController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "banner.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->image_format = '<img src="'.url('/image/banner?file_name='.$data->file_name).'" style="width: 10rem"/>';
      $data->status_publish = $data->is_publish == 1 ? __('general.publish') : __('general.not_publish');
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'banner.index');
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $arr = Banner::select('*', 'file_name as image_format', 'is_publish as status_publish');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(isset($request->is_publish))
      $arr = $arr->where('is_publish', '=', $request->is_publish);

    if(!empty($request->type))
      $arr = $arr->where('type', '=', $request->type);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    if(!empty($request->id)){
      $data = Banner::find($request->id);
    }

    return $this->get_data_helper->return_data($request, [], 'view', 'banner.action', [
      'banner' => $data,
    ]);
  }

  public function multiple_action(Request $request){
    $data = null;
    if(!empty($request->id))
      $data = Banner::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'banner.multiple_add', [
      'banner' => $data,
    ]);
  }

  public function post(Request $request){
    $data = new Banner();
    $data->is_publish = $request->is_publish;
    $data->save();

    if(!empty($request->image) && $request->image != ""){
      $this->file_helper->manage_image($request->image, $data, 'banner');
      $data->save();
    }
    $data->refresh();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/banner');
  }

  public function multiple_post(Request $request){
    $arr_banner = json_decode($request->arr_banner, true);

    foreach($arr_banner as $banner){
      $data = new Banner();
      $data->is_publish = $banner['is_publish'];
      $data->save();

      if(!empty($banner["image"]) && $banner["image"] != ""){
        $this->file_helper->manage_image($banner["image"], $data, 'banner');
        $data->save();
      }
      $data->refresh();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/banner');
  }

  public function put(Request $request){
    $data = Banner::find($request->id);
    $data->is_publish = $request->is_publish;
    $data->save();

    if(!empty($request->image) && $request->image != ""){
      $this->file_helper->manage_image($request->image, $data, 'banner');
      $data->save();
    }
    $data->refresh();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/banner');
  }

  public function delete(Request $request){
    $data = Banner::find($request->id);
    $type = $data->type;
    $data->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/banner');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
