<?php

namespace App\Models;

class AdditionalSalaryDocument extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'additional_salary_document';

  public function application(){
    return $this->belongsTo('App\Models\JobsApplication');
  }
}
