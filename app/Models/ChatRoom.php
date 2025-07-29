<?php

namespace App\Models;

use App\Http\Controllers\Helper\FileHelper;

class ChatRoom extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'chat_room';

  public $arr_relationship = [];

  public $arr_column_image = [
    'file_name' => 'chat',
  ];

  public function chat(){
    return $this->belongsTo('App\Models\Chat');
  }

  public function sender(){
    return $this->belongsTo('App\Models\User', 'sender_id', 'id');
  }

  public function receiver(){
    return $this->belongsTo('App\Models\User', 'receiver_id', 'id');
  }
}
