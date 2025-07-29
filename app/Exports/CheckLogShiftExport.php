<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CheckLogShiftExport implements FromView
{
  private $arr_check_log;
  private $jobs_shift;

  public function __construct($arr_check_log, $jobs_shift){
    $this->arr_check_log = $arr_check_log;
    $this->jobs_shift = $jobs_shift;
  }

  public function view(): View{
    return view('exports.check_log_shift', [
      'arr_check_log' => $this->arr_check_log,
      'jobs_shift' => $this->jobs_shift,
    ]);
  }
}
