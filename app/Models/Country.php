<?php

namespace App\Models;

class Country extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'country';

  public $arr_relationship = ['province', ];

  public function province(){
    return $this->hasMany('App\Models\Province');
  }
}
