<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;

class CategoryController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "category.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $data->allow_delete = count($data->sub_category) == 0;
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.category.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $category_model = new Category();

    $arr = Category::select($category_model->get_table_name().'.*');

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
      $data = Category::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'master.category.action', [
      'category' => $data,
    ]);
  }

  public function multiple(Request $request){
    return $this->get_data_helper->return_data($request, [], 'view', 'master.category.multiple_add', []);
  }

  public function multiple_post(Request $request){
    $arr_category = json_decode($request->arr_category, true);

    foreach($arr_category as $category){
      $data = new Category();
      $data->is_publish = $category['is_publish'];
      $data->name = $category['name'];
      $data->save();

      if(!empty($category["image"]) && $category["image"] != ""){
        $this->file_helper->manage_image($category["image"], $data, 'category');
        $data->save();
      }
      $data->refresh();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/category');
  }

  public function post(Request $request){
    $data = new Category();
    $data->name = $request->name;
    $data->save();

    if(!empty($request->image) && $request->image != ""){
      $this->file_helper->manage_image($request->image, $data, 'category');
      $data->save();
    }
    $data->refresh();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/category');
  }

  public function put(Request $request){
    $data = Category::find($request->id);
    $data->name = $request->name;
    $data->save();

    if(!empty($request->image) && $request->image != ""){
      $this->file_helper->manage_image($request->image, $data, 'category');
      $data->save();
    }
    $data->refresh();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/category');
  }

  public function delete(Request $request){
    Category::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/category');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    foreach($arr as $data){
      $data->total_sub_category_format = $data->total_sub_category;
      foreach($data->sub_category as $sub_category){
        $sub_category->service;
      }
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
