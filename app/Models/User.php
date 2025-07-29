<?php

namespace App\Models;

class User extends BaseAuth
{
  protected $table = 'user';

  public $arr_relationship = ['firebase_token', 'general_quiz_result', 'jobs_recommendation', 'resume', 'notification', 'salary_transaction', 'jobs_application', 'jobs_approve', ];

  public $arr_column_image = [
    'file_name' => 'user',
  ];

  protected $casts = [
    'point_balance' => 'double',
    'xp_balance' => 'double',
  ];

  public function type(){
    return $this->belongsTo('App\Models\Type');
  }

  public function company(){
    return $this->belongsTo('App\Models\Company');
  }

  public function company_position(){
    return $this->belongsTo('App\Models\CompanyPosition');
  }

  public function sub_category(){
    return $this->belongsTo('App\Models\SubCategory');
  }

  public function firebase_token(){
    return $this->hasMany('App\Models\FirebaseToken');
  }

  public function general_quiz_result(){
    return $this->hasMany('App\Models\GeneralQuizResult');
  }

  public function jobs_recommendation(){
    return $this->hasMany('App\Models\JobsRecommendation');
  }

  public function resume(){
    return $this->hasMany('App\Models\Resume');
  }

  public function notification(){
    return $this->hasMany('App\Models\Notification');
  }

  public function salary_transaction(){
    return $this->hasMany('App\Models\SalaryTransaction');
  }

  public function jobs_application(){
    return $this->hasMany('App\Models\JobsApplication');
  }

  public function jobs_approve(){
    return $this->hasMany('App\Models\JobsApprove');
  }
}
