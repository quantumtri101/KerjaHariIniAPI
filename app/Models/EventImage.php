<?php

namespace App\Models;

class EventImage extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'event_image';

  public function event(){
    return $this->belongsTo('App\Models\Event');
  }
}
