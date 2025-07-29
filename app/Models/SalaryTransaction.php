<?php

namespace App\Models;

class SalaryTransaction extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'salary_transaction';

  public $arr_relationship = [];

  public function user(){
    return $this->belongsTo('App\Models\User');
  }
}
