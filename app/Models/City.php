<?php

namespace App\Models;

class City extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'city';

  public $arr_relationship = ['experience',];

  public function province(){
    return $this->belongsTo('App\Models\Province');
  }

  public function experience(){
    return $this->hasMany('App\Models\Experience');
  }
}
