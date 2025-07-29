<?php

namespace Tests\Feature;

use App\Models\Disbursement;
use App\Models\Bank;

class DisbursementTest extends BaseTest
{
  public function test_get(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/disbursement', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_get_all(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->getJson('/api/disbursement/all', []);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  private function manage_data($type = 'POST'){
    $bank = Bank::first();
    $data = Disbursement::first();

    $arr = [
      "bank" => [
        "id" => $bank->id,
      ],
      "acc_no" => "123",
      "acc_name" => "abc",
      "amount" => 10000,
    ];

    if($type == 'PUT')
      $arr['id'] = $data->id;

    return $arr;
  }

  public function test_post(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->postJson('/api/disbursement', $this->manage_data());

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_post_sales(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->postJson('/api/disbursement/sales', $this->manage_data());

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }

  public function test_delete(){
    $this->set_header();
    $response = $this->withHeaders($this->header)->deleteJson('/api/disbursement', $this->manage_data('PUT'));

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
      ]);
  }
}
