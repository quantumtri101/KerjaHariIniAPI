<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\EventHelper;

use App\Models\Event;
use App\Models\Company;

class EventController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "event.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr = $this->manage_where($request);

    foreach($arr as $data){
      $this->relationship_helper->event($data);
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ], 'view', 'event.index', [
      'arr' => $arr,
      'arr_header' => $this->arr_header,
    ]);
  }

  private function manage_where($request, $type = 'index'){
    $this->arr_header = $this->get_data_helper->manage_header($request, $this->arr_header);

    $event_model = new Event();
    $company_model = new Company();

    $arr = Event::select($event_model->get_table_name().'.*', $event_model->get_table_name().'.start_date as date_format', $company_model->get_table_name().'.name as company_name',)
      ->leftJoin($company_model->get_table_name(), $event_model->get_table_name().'.company_id', '=', $company_model->get_table_name().'.id');

    if(!empty($request->id))
      $arr = $arr->where($event_model->get_table_name().'.id', '=', $request->id);

    if(!empty($request->name))
      $arr = $arr->where($event_model->get_table_name().'.name', 'like', '%'.str_replace(' ','%',$request->name).'%');

    if(empty($request->id) && Auth::check() && !empty(Auth::user()->company))
      $arr = $arr->where($event_model->get_table_name().'.company_id', '=', Auth::user()->company->id);

    $arr = $this->get_data_helper->manage_search_sort($request, $this->arr_header, $arr);

    return $this->get_data_helper->manage_get_data($arr, $type, $request);
  }

  public function action(Request $request){
    $data = null;
    $arr_company = Company::all();
    if(!empty($request->id))
      $data = Event::find($request->id);

    return $this->get_data_helper->return_data($request, [], 'view', 'event.action', [
      'event' => $data,
      'arr_company' => $arr_company,
    ]);
  }

  public function detail(Request $request){
    $data = Event::find($request->id);
    $this->relationship_helper->event($data);

    $arr_tab = [
      [
        "id" => "general_info",
        "component" => "event.component.general_info",
      ],
      [
        "id" => "list_customer",
        "component" => "event.component.list_customer",
      ],
      [
        "id" => "calendar",
        "component" => "event.component.calendar",
      ],
    ];

    return $this->get_data_helper->return_data($request, [], 'view', 'event.detail', [
      'event' => $data,
      'arr_tab' => $arr_tab,
    ]);
  }

  public function post(Request $request){
    $helper = new EventHelper();

    $data = new Event();
    $data->company_id = Auth::user()->type->name == "RO" || Auth::user()->type->name == "staff" ? Auth::user()->company->id : $request->company_id;
    $data->name = $request->name;
    $data->start_date = Carbon::createFromFormat('d-m-Y H:i', $request->start_date);
    $data->end_date = Carbon::createFromFormat('d-m-Y H:i', $request->end_date);
    $data->save();
    
    $arr_image = json_decode($request->arr_image, true);
    $helper->edit_image($arr_image, $data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/event');
  }

  public function put(Request $request){
    $helper = new EventHelper();

    $data = Event::find($request->id);
    $data->name = $request->name;
    $data->start_date = Carbon::createFromFormat('d-m-Y H:i', $request->start_date);
    $data->end_date = Carbon::createFromFormat('d-m-Y H:i', $request->end_date);
    $data->save();

    $arr_image = json_decode($request->arr_image, true);
    $helper->edit_image($arr_image, $data);

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/event');
  }

  public function delete(Request $request){
    Event::find($request->id)->delete();

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
    ], 'redirect', '/event');
  }

  public function get_all(Request $request){
    $arr = $this->manage_where($request, 'all');

    foreach($arr as $data){
      $data->total_sub_event_format = $data->total_sub_event;
      foreach($data->sub_event as $sub_event){
        $sub_event->service;
      }
    }

    return $this->get_data_helper->return_data($request, [
      'status' => 'success',
      'data' => !empty($request->id) ? $arr[0] : $arr,
    ]);
  }
}
