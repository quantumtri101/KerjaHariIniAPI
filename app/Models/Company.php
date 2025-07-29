<?php

namespace App\Models;

class Company extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'company';

  public $arr_relationship = ['staff', 'event', 'jobs',];

  public function category(){
    return $this->belongsTo('App\Models\Category');
  }

  public function city(){
    return $this->belongsTo('App\Models\City');
  }

  public function staff(){
    return $this->hasMany('App\Models\User');
  }

  public function event(){
    return $this->hasMany('App\Models\Event');
  }

  public function jobs(){
    return $this->hasMany('App\Models\Jobs');
  }
}
