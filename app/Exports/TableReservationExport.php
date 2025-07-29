<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\Reservation;

class TableReservationExport implements FromView
{
  private $arr_reservation;
  private $branch;

  public function __construct($arr_reservation, $branch){
    $this->arr_reservation = $arr_reservation;
    $this->branch = $branch;
  }

  public function view(): View
  {
    return view('exports.table_reservation', [
      'arr_reservation' => $this->arr_reservation,
      'branch' => $this->branch,
    ]);
  }
}
