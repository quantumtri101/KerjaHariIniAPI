<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\EventHelper;

use App\Models\Event;
use App\Models\Company;

class CalendarController extends BaseController{
  private $arr_header = [
    [
      "id" => "name",
      "column" => "event.name",
      "name" => "general.name",
      "data_type" => "string",
    ],
  ];

  public function index(Request $request){
    $arr_event = Event::all();
    foreach($arr_event as $event)
      $this->relationship_helper->event($event);

    return $this->get_data_helper->return_data($request, [], 'view', 'calendar.calendar', [
      'arr_event' => $arr_event,
    ]);
  }
}
