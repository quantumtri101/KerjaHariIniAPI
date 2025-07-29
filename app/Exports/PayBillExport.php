<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PayBillExport implements FromView
{
  private $arr_pay_bill;
  private $branch;

  public function __construct($arr_pay_bill, $branch){
    $this->arr_pay_bill = $arr_pay_bill;
    $this->branch = $branch;
  }

  public function view(): View
  {
    return view('exports.pay_bill', [
      'arr_pay_bill' => $this->arr_pay_bill,
      'branch' => $this->branch,
    ]);
  }
}
