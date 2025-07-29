<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsApproveTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_approve', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs1_id',100)->nullable();
      $table->string('user_id', 100)->nullable();
      $table->boolean('is_approve')->default(0);

      $table->foreign('jobs1_id')->references('id')->on('jobs1')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs_approve');
  }
}
