<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\SubCategory;
use App\Models\Category;

class SubCategoryController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "sub_category.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->allow_delete = count($data->jobs) == 0;
      $data->image_format = '<img src="'.(!empty($data->file_name) ? url('/image/sub-category?file_name='.$data->file_name) : $this->url_asset."/image/no_image_available.jpeg").'" style="width: 10rem"/>';
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.sub_category.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $sub_category_model = new SubCategory();
    $category_model = new Category();

    $arr = SubCategory::select($sub_category_model->get_table_name().'.*', $category_model->get_table_name().'.name as category_name', $sub_category_model->get_table_name().'.file_name as image_format',)
      ->join($category_model->get_table_name(), $sub_category_model->get_table_name().'.category_id', '=', $category_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($sub_category_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->category_id))
      $arr = $arr->where($sub_category_model->get_table_name().'.category_id', '=', $request->category_id);

    if(!empty($request->name))
      $arr = $arr->where($sub_category_model->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    $arr_category = Category::all();
    if(!empty($request->id))
      $data = SubCategory::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'master.sub_category.action', [
      'sub_category' => $data,
      'arr_category' => $arr_category,
    ]);
  }

  public function multiple(Request $request){
    $arr_category = Category::all();

    return $this->get_data_helper->return_data($request, [], 'view', 'master.sub_category.multiple_add', [
      'arr_category' => $arr_category,
    ]);
  }

  public function multiple_post(Request $request){
    $arr_sub_category = json_decode($request->arr_sub_category, true);

    foreach($arr_sub_category as $sub_category){
      $data = new SubCategory();
      $data->category_id = $request->category_id;
      // $data->is_publish = $sub_category['is_publish'];
      $data->name = $sub_category['name'];
      $data->save();

      if(!empty($sub_category["image"]) && $sub_category["image"] != ""){
        $this->file_helper->manage_image($sub_category["image"], $data, 'sub_category');
        $data->save();
      }
      $data->refresh();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/sub-category');
  }

  public function post(Request $request){
    $data = new SubCategory();
    $data->category_id = $request->category_id;
    $data->name = $request->name;
    $data->save();

    if(!empty($request->image) && $request->image != ""){
      $this->file_helper->manage_image($request->image, $data, 'sub_category');
      $data->save();
    }
    $data->refresh();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/sub-category');
  }

  public function put(Request $request){
    $data = SubCategory::find($request->id);
    $data->category_id = $request->category_id;
    $data->name = $request->name;
    $data->save();

    if(!empty($request->image) && $request->image != ""){
      $this->file_helper->manage_image($request->image, $data, 'sub_category');
      $data->save();
    }
    $data->refresh();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/sub-category');
  }

  public function delete(Request $request){
    SubCategory::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/sub-category');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
