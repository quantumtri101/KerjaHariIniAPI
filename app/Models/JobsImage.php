<?php

namespace App\Models;

class JobsImage extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_image';

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs', 'jobs1_id', 'id');
  }
}
