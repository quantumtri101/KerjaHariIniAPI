<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserRegularExport implements FromView
{
  private $arr_user;

  public function __construct($arr_user){
    $this->arr_user = $arr_user;
  }

  public function view(): View{
    return view('exports.customer_regular	', [
      'arr_user' => $this->arr_user,
    ]);
  }
}
