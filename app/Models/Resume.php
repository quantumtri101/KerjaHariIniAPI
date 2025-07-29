<?php

namespace App\Models;

class Resume extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'resume';

  public $arr_relationship = ['experience', 'jobs_application', 'skill',];

  public function city(){
    return $this->belongsTo('App\Models\City');
  }

  public function bank(){
    return $this->belongsTo('App\Models\Bank');
  }

  public function education(){
    return $this->belongsTo('App\Models\Education');
  }

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function experience(){
    return $this->hasMany('App\Models\Experience');
  }

  public function jobs_application(){
    return $this->hasMany('App\Models\JobsApplication');
  }

  public function skill(){
    return $this->hasMany('App\Models\ResumeSkill');
  }
}
