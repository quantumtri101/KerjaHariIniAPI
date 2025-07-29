<?php

namespace Tests\Feature;

use App\Models\Rating;
use App\Models\Order;

class RatingTest extends BaseTest
{
  public function test_get(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/rating', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_get_all(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/rating/all', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  private function manage_data($type = 'POST'){
    $order = Order::first();
    $data = Rating::first();

    $arr = [
      "order" => [
        "id" => $order->id,
      ],
      "rating" => 0,
      "review" => "",
    ];

    if($type == 'PUT')
      $arr['id'] = $data->id;

    return $arr;
  }

  public function test_post(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->postJson('/api/rating', $this->manage_data());

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_put(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->putJson('/api/rating', $this->manage_data('PUT'));

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_delete(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->deleteJson('/api/rating', $this->manage_data('PUT'));

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
