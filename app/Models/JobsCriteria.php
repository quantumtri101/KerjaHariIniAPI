<?php

namespace App\Models;

class JobsCriteria extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_criteria';

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs', 'jobs1_id', 'id');
  }

  public function education(){
    return $this->belongsTo('App\Models\Education');
  }
}
