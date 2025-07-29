<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransferTransactionExport implements FromView
{
  private $arr_transfer_transaction;
  private $branch;

  public function __construct($arr_transfer_transaction, $branch){
    $this->arr_transfer_transaction = $arr_transfer_transaction;
    $this->branch = $branch;
  }

  public function view(): View
  {
    return view('exports.transfer_transaction', [
      'arr_transfer_transaction' => $this->arr_transfer_transaction,
      'branch' => $this->branch,
    ]);
  }
}
