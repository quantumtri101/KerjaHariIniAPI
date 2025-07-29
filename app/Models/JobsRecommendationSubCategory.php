<?php

namespace App\Models;

class JobsRecommendationSubCategory extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_recommendation_sub_category';

  public function jobs_recommendation(){
    return $this->belongsTo('App\Models\JobsRecommendation');
  }

  public function sub_category(){
    return $this->belongsTo('App\Models\SubCategory');
  }
}
