<?php

namespace App\Models;

class SalaryDocument extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'salary_document';

  public function application(){
    return $this->belongsTo('App\Models\JobsApplication');
  }
}
