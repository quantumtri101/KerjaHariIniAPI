<?php

namespace App\Models;

class PaymentMethod extends Base
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'payment_method';

  public $arr_relationship = [];
}
