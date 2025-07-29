<?php

namespace App\Models;

class GeneralQuizResult extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'general_quiz_result';

  public $arr_relationship = ['jobs_application',];

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function general_quiz_answer(){
    return $this->hasMany('App\Models\GeneralQuizAnswer');
  }

  public function jobs_application(){
    return $this->hasMany('App\Models\JobsApplication');
  }
}
