<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateCheckLogTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('check_log', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs_application_id',100)->nullable();
      $table->string('jobs1_id',100)->nullable();
      $table->enum('type', ['check_in', 'check_out'])->default('check_in');
      $table->dateTime('date')->nullable();

      $table->foreign('jobs1_id')->references('id')->on('jobs1')->onDelete('cascade');
      $table->foreign('jobs_application_id')->references('id')->on('jobs_application')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('check_log');
  }
}
