<?php

namespace App\Models;

class JobsApplied extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_applied';

  public function jobs_application(){
    return $this->belongsTo('App\Models\JobsApplication');
  }
}
