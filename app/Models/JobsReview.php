<?php

namespace App\Models;

class JobsReview extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_review';

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs', 'jobs1_id', 'id');
  }

  public function user1(){
    return $this->belongsTo('App\Models\User', 'user1_id', 'id');
  }

  public function user2(){
    return $this->belongsTo('App\Models\User', 'user2_id', 'id');
  }
}
