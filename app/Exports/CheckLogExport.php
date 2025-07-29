<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CheckLogExport implements FromView
{
  private $arr_jobs_shift;

  public function __construct($arr_jobs_shift){
    $this->arr_jobs_shift = $arr_jobs_shift;
  }

  public function view(): View{
    return view('exports.check_log	', [
      'arr_jobs_shift' => $this->arr_jobs_shift,
    ]);
  }
}
