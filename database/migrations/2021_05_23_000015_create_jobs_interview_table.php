<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsInterviewTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_interview', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs_application_id',100)->nullable();
      $table->string('user_id',100)->nullable();
      $table->text('schedule')->nullable();
      $table->enum('type', ["online", "offline", ])->default('offline');
      $table->string('zoom_url', 100)->nullable();
      $table->text('location')->nullable();
      $table->longText('notes')->nullable();

      $table->foreign('jobs_application_id')->references('id')->on('jobs_application')->onDelete('cascade');
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
    Schema::dropIfExists('jobs_interview');
  }
}
