<?php

namespace App\Models;

class JobsWorkingArea extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_working_area';

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs', 'jobs1_id', 'id');
  }

  public function city(){
    return $this->belongsTo('App\Models\City');
  }
}
