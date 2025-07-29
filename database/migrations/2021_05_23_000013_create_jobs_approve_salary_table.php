<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsApproveSalaryTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_approve_salary', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs1_id',100)->nullable();
      $table->string('user_id', 100)->nullable();
      $table->enum('status_approve', ["approved", 'declined', 'not_yet_approved'])->default('not_yet_approved');
      $table->datetime('approved_at')->nullable();
      $table->longText('decline_reason')->nullable();

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
    Schema::dropIfExists('jobs_approve_salary');
  }
}
