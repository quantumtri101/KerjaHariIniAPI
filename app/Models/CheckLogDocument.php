<?php

namespace App\Models;

class CheckLogDocument extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'check_log_document';

  public function check_log(){
    return $this->belongsTo('App\Models\CheckLog');
  }
}
