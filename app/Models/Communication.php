<?php

namespace App\Models;

class Communication extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'communication';

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function branch(){
    return $this->belongsTo('App\Models\Branch');
  }

  public function outlet(){
    return $this->belongsTo('App\Models\Outlet');
  }

  public function type(){
    return $this->belongsTo('App\Models\Type');
  }
}
