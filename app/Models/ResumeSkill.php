<?php

namespace App\Models;

class ResumeSkill extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'resume_skill';

  public function skill(){
    return $this->belongsTo('App\Models\Skill');
  }

  public function resume(){
    return $this->belongsTo('App\Models\Resume');
  }
}
