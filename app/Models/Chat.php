<?php

namespace App\Models;

use App\Http\Controllers\Helper\FileHelper;

class Chat extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'chat';

  public $arr_relationship = ['chat_room', ];

  public function person_1(){
    return $this->belongsTo('App\Models\User', 'person_1_id', 'id');
  }

  public function person_2(){
    return $this->belongsTo('App\Models\User', 'person_2_id', 'id');
  }

  public function order(){
    return $this->belongsTo('App\Models\Order');
  }

  public function chat_room(){
    return $this->hasMany('App\Models\ChatRoom');
  }
}
