<?php

namespace App\Models;

class Education extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'education';

  public $arr_relationship = ['resume',];

  public function resume(){
    return $this->hasMany('App\Models\Resume');
  }
}
