<?php

namespace App\Models;

class JobsRangeSalary extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_range_salary';

  public $arr_relationship = ['jobs_recommendation',];

  public function jobs_recommendation(){
    return $this->hasMany('App\Models\JobsRecommendation');
  }
}
