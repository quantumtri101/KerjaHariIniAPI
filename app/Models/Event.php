<?php

namespace App\Models;

class Event extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'event';

  public $arr_relationship = ['jobs', 'image',];

  public function company(){
    return $this->belongsTo('App\Models\Company');
  }

  public function jobs(){
    return $this->hasMany('App\Models\Jobs');
  }

  public function image(){
    return $this->hasMany('App\Models\EventImage');
  }
}
