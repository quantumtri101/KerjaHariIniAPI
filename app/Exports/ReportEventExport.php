<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportEventExport implements FromView
{
  private $arr;

  public function __construct($arr){
    $this->arr = $arr;
  }

  public function view(): View{
    return view('exports.report_event', [
      'arr' => $this->arr,
    ]);
  }
}
