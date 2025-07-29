<?php

namespace Tests\Feature;

use App\Models\Notification;

class NotificationTest extends BaseTest
{
  public function test_get(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/notification', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_set_read(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->putJson('/api/notification/read', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
