<?php

namespace App\Models;

class Notification extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'notification';

  public function user(){
    return $this->belongsTo('App\Models\User');
  }
}
