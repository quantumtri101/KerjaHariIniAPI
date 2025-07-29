<?php

namespace App\Models;

class JobsApplication extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs_application';

  public $arr_relationship = ['jobs_applied', 'jobs_interview', 'check_log', 'salary_document', 'additional_salary_document',];

  public function general_quiz_result(){
    return $this->belongsTo('App\Models\GeneralQuizResult');
  }

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function resume(){
    return $this->belongsTo('App\Models\Resume');
  }

  public function jobs(){
    return $this->belongsTo('App\Models\Jobs', 'jobs1_id', 'id');
  }

  public function jobs_applied(){
    return $this->hasMany('App\Models\JobsApplied');
  }

  public function jobs_interview(){
    return $this->hasMany('App\Models\JobsInterview');
  }

  public function check_log(){
    return $this->hasMany('App\Models\CheckLog');
  }

  public function salary_document(){
    return $this->hasMany('App\Models\SalaryDocument');
  }

  public function additional_salary_document(){
    return $this->hasMany('App\Models\AdditionalSalaryDocument');
  }
}
