<?php

namespace Tests\Feature;

use App\Models\User;

class AuthTest extends BaseTest
{
  public function test_login(){
    $this->set_header(false);
    $arr = [
      "email" => "admin@admin.com",
      "password" => "12345",
    ];

    $response = $this->withHeaders($this->header)->postJson('/api/auth/login', $arr);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_register(){
    $this->set_header(false);
    $arr = [
      "email" => "abc@abc.abc",
      "password" => "12345",
      "name" => "test",
      "phone" => "test",
      "type" => "sales"
    ];

    $response = $this->withHeaders($this->header)->postJson('/api/auth/register', $arr);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_forget_password(){
    $this->set_header();
    $arr = [
      "email" => "admin@admin.com",
    ];

    $response = $this->withHeaders($this->header)->postJson('/api/auth/forget-password', $arr);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_change_password(){
    $this->set_header();
    $arr = [
      "old_password" => "12345",
      "new_password" => "12345",
    ];

    $response = $this->withHeaders($this->header)->putJson('/api/auth/change-password', $arr);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_change_profile(){
    $this->set_header();
    $arr = [
      "name" => "test",
      "phone" => "test",
      'email' => 'test@test.com',
    ];

    $response = $this->withHeaders($this->header)->putJson('/api/auth/change-profile', $arr);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_get_profile(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/auth/profile', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
