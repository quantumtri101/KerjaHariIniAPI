<?php

namespace App\Models;

class JobsShift extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_shift';

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs', 'jobs1_id', 'id');
  }
}
