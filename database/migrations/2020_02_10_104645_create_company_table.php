<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateCompanyTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('company', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('name', 100);
      $table->string('address', 100)->nullable();
      $table->string('phone', 100)->nullable();
      $table->string('file_name', 100)->nullable();
      $table->double('latitude', 10, 2)->default(0);
      $table->double('longitude', 10, 2)->default(0);
      $table->boolean('is_publish')->default(1);
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('company');
  }
}
