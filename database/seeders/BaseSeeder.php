<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\BaseController;

class BaseSeeder extends Seeder
{
  public $base;

  public function __construct(){
    $this->base = new BaseController();
  }
}
