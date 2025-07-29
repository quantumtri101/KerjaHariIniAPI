<?php

namespace App\Models;

class CheckLog extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'check_log';

  public function jobs_application(){
    return $this->belongsTo('App\Models\JobsApplication');
  }

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs');
  }

  public function jobs_shift(){
    return $this->belongsTo('App\Models\JobsShift');
  }

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function document(){
    return $this->hasMany('App\Models\CheckLogDocument');
  }
}
