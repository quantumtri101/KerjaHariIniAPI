<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Cart;
use App\Models\Voucher;

class OrderTest extends BaseTest
{
  public function test_get(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/order', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_get_all(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/order/all', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  private function manage_data($type = 'POST'){
    $payment_method = PaymentMethod::first();
    $cart = Cart::first();
    $voucher = Voucher::first();
    $data = Order::first();

    $arr = [
      "payment_method" => [
        "id" => $payment_method->id,
      ],
      "cart" => [
        "id" => $cart->id,
      ],
      "voucher" => [
        "id" => $voucher->id,
      ],
    ];

    if($type == 'PUT')
      $arr['id'] = $data->id;

    return $arr;
  }

  public function test_post(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->postJson('/api/order', $this->manage_data());

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
