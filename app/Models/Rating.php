<?php

namespace App\Models;

class Rating extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'rating';

  public $arr_relationship = [];

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function jobs_application(){
    return $this->belongsTo('App\Models\JobsApplication');
  }

  public function staff(){
    return $this->belongsTo('App\Models\User', 'staff_id', 'id');
  }
}
