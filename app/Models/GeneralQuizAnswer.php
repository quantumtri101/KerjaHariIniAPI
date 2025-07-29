<?php

namespace App\Models;

class GeneralQuizAnswer extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'general_quiz_answer';

  public function general_quiz_option(){
    return $this->belongsTo('App\Models\GeneralQuizOption');
  }

  public function general_quiz_result(){
    return $this->belongsTo('App\Models\GeneralQuizResult');
  }
}
