<?php

namespace App\Models;

class JobsRecommendation extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_recommendation';

  public $arr_relationship = ['sub_category', 'city',];

  // public function city(){
  //   return $this->belongsTo('App\Models\City');
  // }

  // public function category(){
  //   return $this->belongsTo('App\Models\Category');
  // }

  public function range_salary(){
    return $this->belongsTo('App\Models\JobsRangeSalary', 'jobs_range_salary_id', 'id');
  }

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function sub_category(){
    return $this->hasMany('App\Models\JobsRecommendationSubCategory');
  }

  public function city(){
    return $this->hasMany('App\Models\JobsRecommendationCity');
  }
}
