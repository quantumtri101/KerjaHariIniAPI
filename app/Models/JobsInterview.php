<?php

namespace App\Models;

class JobsInterview extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_interview';

  public function jobs_application(){
    return $this->belongsTo('App\Models\JobsApplication');
  }
}
