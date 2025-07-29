<?php

namespace Tests\Feature;

use App\Models\Voucher;

class VoucherTest extends BaseTest
{
  public function test_get(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/voucher', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_get_all(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/voucher/all', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
