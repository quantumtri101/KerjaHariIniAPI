<?php

namespace App\Models;

class JobsRecommendationCity extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_recommendation_city';

  public function jobs_recommendation(){
    return $this->belongsTo('App\Models\JobsRecommendation');
  }

  public function city(){
    return $this->belongsTo('App\Models\City');
  }
}
