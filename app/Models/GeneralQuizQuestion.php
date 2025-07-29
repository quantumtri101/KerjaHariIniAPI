<?php

namespace App\Models;

class GeneralQuizQuestion extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'general_quiz_question';

  public $arr_relationship = ['general_quiz_option',];

  public function general_quiz_option(){
    return $this->hasMany('App\Models\GeneralQuizOption');
  }
}
