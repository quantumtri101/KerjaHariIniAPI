<?php

namespace App\Models;

class SubCategory extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'sub_category';

  public $arr_relationship = ['jobs_recommendation',];

  public function category(){
    return $this->belongsTo('App\Models\Category');
  }

  public function jobs(){
    return $this->hasMany('App\Models\Jobs');
  }

  public function jobs_recommendation(){
    return $this->hasMany('App\Models\JobsRecommendationSubCategory');
  }
}
