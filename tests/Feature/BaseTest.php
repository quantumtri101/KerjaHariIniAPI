<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\User;
use App\Models\Auth;

class BaseTest extends TestCase
{
  use DatabaseTransactions;

  protected $header = [];
  protected $seed = true;

  protected function set_header($with_token = true){
    $user = User::orderBy('created_at')->first();
    if($with_token)
      Sanctum::actingAs($user, ['*']);

    $this->header = [
      'Accept' => 'application/json',
    ];
  }

  public function test(){
    $this->assertTrue(true);
  }
}
