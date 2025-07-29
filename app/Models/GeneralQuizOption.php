<?php

namespace App\Models;

class GeneralQuizOption extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'general_quiz_option';

  public $arr_relationship = ['general_quiz_answer',];

  public function general_quiz_question(){
    return $this->belongsTo('App\Models\GeneralQuizQuestion');
  }

  public function general_quiz_answer(){
    return $this->hasMany('App\Models\GeneralQuizAnswer');
  }
}
