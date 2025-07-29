<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportMonthlyExport implements FromView
{
  private $arr_month;

  public function __construct($arr_month){
    $this->arr_month = $arr_month;
  }

  public function view(): View{
    return view('exports.report_monthly', [
      'arr_month' => $this->arr_month,
    ]);
  }
}
