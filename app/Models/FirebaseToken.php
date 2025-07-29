<?php

namespace App\Models;

class FirebaseToken extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'firebase_token';

  public function user(){
    return $this->belongsTo('App\Models\User');
  }
}
