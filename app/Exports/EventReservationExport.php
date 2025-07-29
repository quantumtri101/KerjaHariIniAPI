<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\Reservation;

class EventReservationExport implements FromView
{
  private $arr_reservation;
  private $event;

  public function __construct($arr_reservation, $event){
    $this->arr_reservation = $arr_reservation;
    $this->event = $event;
  }

  public function view(): View
  {
    return view('exports.event_reservation', [
      'arr_reservation' => $this->arr_reservation,
      'event' => $this->event,
    ]);
  }
}
