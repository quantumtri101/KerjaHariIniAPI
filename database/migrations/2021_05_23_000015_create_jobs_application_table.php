<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsApplicationTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_application', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('general_quiz_result_id',100)->nullable();
      $table->string('user_id',100)->nullable();
      $table->string('resume_id',100)->nullable();
      $table->string('jobs1_id',100)->nullable();
      $table->text('content')->nullable();
      $table->longText('first_question')->nullable();
      $table->enum('status', ["wait", "interview", "accepted", "working", "declined", "done"])->default('wait');

      $table->foreign('general_quiz_result_id')->references('id')->on('general_quiz_result')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
      $table->foreign('resume_id')->references('id')->on('resume')->onDelete('cascade');
      $table->foreign('jobs1_id')->references('id')->on('jobs1')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs_application');
  }
}
