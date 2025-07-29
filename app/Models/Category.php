<?php

namespace App\Models;

class Category extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'category';

  public $arr_relationship = ['sub_category',];

  public function sub_category(){
    return $this->hasMany('App\Models\SubCategory');
  }
}
