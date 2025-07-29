<?php

namespace App\Models;

class JobsApproveSalary extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_approve_salary';

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs', 'jobs1_id', 'id');
  }

  public function user(){
    return $this->belongsTo('App\Models\User');
  }
}
