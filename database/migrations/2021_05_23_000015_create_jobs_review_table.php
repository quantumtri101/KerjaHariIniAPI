<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsReviewTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_review', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs1_id',100)->nullable();
      $table->string('user1_id', 100)->nullable();
      $table->string('user2_id', 100)->nullable();
      $table->string('company_id', 100)->nullable();
      $table->double('rating', 20, 2)->default(0);
      $table->longText('review')->nullable();

      $table->foreign('jobs1_id')->references('id')->on('jobs1')->onDelete('cascade');
      $table->foreign('user1_id')->references('id')->on('user')->onDelete('cascade');
      $table->foreign('user2_id')->references('id')->on('user')->onDelete('cascade');
      $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs_review');
  }
}
