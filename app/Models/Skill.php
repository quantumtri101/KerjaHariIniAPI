<?php

namespace App\Models;

class Skill extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'skill';

  public $arr_relationship = ['resume',];

  public function resume(){
    return $this->hasMany('App\Models\ResumeSkill');
  }
}
