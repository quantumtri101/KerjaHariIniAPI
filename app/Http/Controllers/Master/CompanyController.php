<?php
namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\MapsPlaceHelper;

use App\Models\Company;
use App\Models\Category;
use App\Models\Type;
use App\Models\Province;
use App\Models\City;
use App\Models\User;

class CompanyController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "company.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $staff = User::where('company_id', '=', $data->id)->first();
      $data->allow_delete = empty($staff);
      $data->category_name = !empty($data->category) ? $data->category->name : '-';
      $data->city_name = !empty($data->city) ? $data->city->name : '-';
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'master.company.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $company_model = new Company();
    $category_model = new Category();
    $city_model = new City();

    $arr = Company::select($company_model->get_table_name().'.*', $category_model->get_table_name().'.name as category_name', $city_model->get_table_name().'.name as city_name',)
      ->leftJoin($category_model->get_table_name(), $company_model->get_table_name().'.category_id', '=', $category_model->get_table_name().'.id')
      ->leftJoin($city_model->get_table_name(), $company_model->get_table_name().'.city_id', '=', $city_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where('id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where('name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $arr_category = Category::all();
    $arr_province = Province::all();
    $arr_city = [];
    $data = null;
    if(!empty($request->id)){
      $data = Company::find($request->id);
      $arr_city = City::where('province_id', '=', $data->city->province->id)->get();
    }

    return $this->get_data_helper->return_data($request, [], 'view', 'master.company.action', [
      'company' => $data,
      'arr_category' => $arr_category,
      'arr_province' => $arr_province,
      'arr_city' => $arr_city,
    ]);
  }

  public function detail(Request $request){
    $data = Company::find($request->id);

    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "master.company.component.general_info",
      ],
      [
        "id" => "list_staff",
        "component" => "master.company.component.list_user",
      ],
      [
        "id" => "maps_info",
        "component" => "master.company.component.maps_info",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'master.company.detail', [
      'company' => $data,
      'arr_tab' => $arr_tab,
    ]);
  }

  public function multiple(Request $request){
    return $this->get_data_helper->return_data($request, [], 'view', 'master.company.multiple_add', []);
  }

  public function multiple_post(Request $request){
    $arr_company = json_decode($request->arr_company, true);

    foreach($arr_company as $company){
      $data = new Company();
      $data->category_id = $company['category_id'];
      $data->city_id = $request->city_id;
      $data->is_publish = $company['is_publish'];
      $data->name = $company['name'];
      $data->address = $company['address'];
      $data->phone = $company['phone'];
      $data->save();

      if(!empty($company["image"]) && $company["image"] != ""){
        $this->file_helper->manage_image($company["image"], $data, 'company');
        $data->save();
      }
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => $data,
    ], 'redirect', '/master/company');
  }

  public function post(Request $request){
    $maps_helper = new MapsPlaceHelper();

    $data = new Company();
    $data->category_id = $request->category_id;
    $data->city_id = $request->city_id;
    $data->name = $request->name;
    $data->address = $request->address;
    $data->phone = "+62".$request->phone;
    $data->save();

    $position = $maps_helper->get_place_search($data->address);
    if(!empty($position["latitude"]) && !empty($position["longitude"])){
      $data->latitude = $position["latitude"];
      $data->longitude = $position["longitude"];
      $data->save();
    }

    if(!empty($request->image) && $request->image != ""){
      $this->file_helper->manage_image($request->image, $data, 'company');
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/company/detail?id='.$data->id);
  }

  public function put(Request $request){
    $maps_helper = new MapsPlaceHelper();

    $data = Company::find($request->id);
    $data->category_id = $request->category_id;
    $data->city_id = $request->city_id;
    $data->name = $request->name;
    $data->address = $request->address;
    $data->phone = "+62".$request->phone;
    $data->save();

    $position = $maps_helper->get_place_search($data->address);
    if(!empty($position["latitude"]) && !empty($position["longitude"])){
      $data->latitude = $position["latitude"];
      $data->longitude = $position["longitude"];
      $data->save();
    }

    if(!empty($request->image) && $request->image != ""){
      $this->file_helper->manage_image($request->image, $data, 'company');
      $data->save();
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/company/detail?id='.$data->id);
  }

  public function delete(Request $request){
    Company::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/master/company');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    foreach($arr as $data){
      $data->total_sub_company_format = $data->total_sub_company;
      foreach($data->sub_company as $sub_company){
        $sub_company->service;
      }
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
