<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsAppliedTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_applied', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs_application_id',100)->nullable();
      $table->string('user_id',100)->nullable();
      $table->dateTime('brief_schedule')->nullable();
      $table->text('brief_location')->nullable();
      $table->dateTime('work_schedule')->nullable();
      $table->text('work_location')->nullable();
      $table->double('latitude', 20, 2)->default(0);
      $table->double('longitude', 20, 2)->default(0);

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
    Schema::dropIfExists('jobs_applied');
  }
}
