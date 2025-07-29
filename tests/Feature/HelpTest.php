<?php

namespace Tests\Feature;

use App\Models\Help;

class HelpTest extends BaseTest
{
  public function test_get(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/help', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_get_all(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/help/all', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
