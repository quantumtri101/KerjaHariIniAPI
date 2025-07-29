<?php

namespace App\Models;

class JobsDocument extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_document';

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs', 'jobs1_id', 'id');
  }

  public function approve(){
    return $this->belongsTo('App\Models\JobsApprove');
  }
}
