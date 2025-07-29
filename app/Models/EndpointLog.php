<?php

namespace App\Models;

use App\Http\Controllers\BaseController;

class EndpointLog extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'endpoint_log';

  public function get_id($data){
    $base_controller = new BaseController();
    return $base_controller->id_helper->generate_new_id_with_date('LOG', $data);
  }

  public function user(){
    return $this->belongsTo('App\Models\User');
  }
}
