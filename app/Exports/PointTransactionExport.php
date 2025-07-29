<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PointTransactionExport implements FromView
{
  private $arr_point_transaction;
  private $branch;

  public function __construct($arr_point_transaction, $branch){
    $this->arr_point_transaction = $arr_point_transaction;
    $this->branch = $branch;
  }

  public function view(): View
  {
    return view('exports.point_transaction', [
      'arr_point_transaction' => $this->arr_point_transaction,
      'branch' => $this->branch,
    ]);
  }
}
