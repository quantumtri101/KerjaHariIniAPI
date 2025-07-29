<?php

namespace App\Models;

class Experience extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'experience';

  public function city(){
    return $this->belongsTo('App\Models\City');
  }

  public function resume(){
    return $this->belongsTo('App\Models\Resume');
  }
}
