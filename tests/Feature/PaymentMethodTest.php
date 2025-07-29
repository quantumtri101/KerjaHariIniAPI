<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;

class PaymentMethodTest extends BaseTest
{
  public function test_get(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/payment-method', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_get_all(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/payment-method/all', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
