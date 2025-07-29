<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreatePaymentMethodTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('payment_method', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('name', 100);
      $table->string('data', 100)->nullable();
      $table->string('channel', 100)->nullable();
      $table->string('code', 100)->nullable();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('payment_method');
  }
}
