<?php

namespace App\Models;

class RequestWithdraw extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'request_withdraw';

  public $arr_relationship = [];

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function bank(){
    return $this->belongsTo('App\Models\Bank');
  }
}
