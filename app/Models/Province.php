<?php

namespace App\Models;

class Province extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'province';

  public $arr_relationship = ['city', ];

  public function city(){
    return $this->hasMany('App\Models\City');
  }

  public function country(){
    return $this->belongsTo('App\Models\Country');
  }
}
