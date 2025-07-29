<?php

namespace Tests\Feature;

use App\Models\BonusLevel;

class BonusLevelTest extends BaseTest
{
  public function test_get(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/bonus-level', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_get_all(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/bonus-level/all', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
