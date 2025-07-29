<?php

namespace App\Models;

class Jobs extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'jobs1';

  public $arr_relationship = ['application', 'image', 'qualification', 'check_log', 'approve', 'interview', 'briefing', 'shift', 'criteria', 'document', 'working_area', ];

  public function sub_category(){
    return $this->belongsTo('App\Models\SubCategory');
  }

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function city(){
    return $this->belongsTo('App\Models\City');
  }

  public function company(){
    return $this->belongsTo('App\Models\Company');
  }

  public function event(){
    return $this->belongsTo('App\Models\Event');
  }

  public function company_position(){
    return $this->belongsTo('App\Models\CompanyPosition');
  }

  public function application(){
    return $this->hasMany('App\Models\JobsApplication', 'jobs1_id', 'id');
  }

  public function image(){
    return $this->hasMany('App\Models\JobsImage', 'jobs1_id', 'id');
  }

  public function qualification(){
    return $this->hasMany('App\Models\JobsQualification', 'jobs1_id', 'id');
  }

  public function check_log(){
    return $this->hasMany('App\Models\CheckLog', 'jobs1_id', 'id');
  }

  public function approve(){
    return $this->hasMany('App\Models\JobsApprove', 'jobs1_id', 'id');
  }

  public function approve_check_log(){
    return $this->hasMany('App\Models\JobsApproveCheckLog', 'jobs1_id', 'id');
  }

  public function approve_salary(){
    return $this->hasMany('App\Models\JobsApproveSalary', 'jobs1_id', 'id');
  }

  public function interview(){
    return $this->hasMany('App\Models\JobsInterview', 'jobs1_id', 'id');
  }

  public function briefing(){
    return $this->hasMany('App\Models\JobsBriefing', 'jobs1_id', 'id');
  }

  public function shift(){
    return $this->hasMany('App\Models\JobsShift', 'jobs1_id', 'id');
  }

  public function criteria(){
    return $this->hasMany('App\Models\JobsCriteria', 'jobs1_id', 'id');
  }

  public function document(){
    return $this->hasMany('App\Models\JobsDocument', 'jobs1_id', 'id');
  }

  public function working_area(){
    return $this->hasMany('App\Models\JobsWorkingArea', 'jobs1_id', 'id');
  }
}
