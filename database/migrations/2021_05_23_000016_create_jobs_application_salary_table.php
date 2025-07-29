<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsApplicationSalaryTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_application_salary', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs_application_id',100)->nullable();
      $table->string('jobs_salary_id',100)->nullable();
      $table->boolean('is_approve')->default(0);
      $table->boolean('is_sent')->default(0);
      $table->dateTime('sent_at')->nullable();

      $table->foreign('jobs_application_id')->references('id')->on('jobs_application')->onDelete('cascade');
      $table->foreign('jobs_salary_id')->references('id')->on('jobs_salary')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs_application_salary');
  }
}
