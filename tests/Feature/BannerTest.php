<?php

namespace Tests\Feature;

use App\Models\Banner;

class BannerTest extends BaseTest
{
  public function test_get_all(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/banner/all', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
