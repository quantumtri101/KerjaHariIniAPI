<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsCriteriaTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_criteria', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs1_id',100)->nullable();
      $table->string('education_id',100)->nullable();
      $table->enum('gender',['male', 'female', 'both'])->default('male');
      $table->double('min_age',20, 2)->default(0);
      $table->double('max_age',20, 2)->default(0);
      $table->boolean('has_pkwt')->default(0);
      $table->boolean('has_pkhl')->default(0);
      $table->boolean('is_working_same_company')->default(0);
      $table->boolean('is_same_place')->default(0);
      $table->longText('other')->nullable();

      $table->foreign('jobs1_id')->references('id')->on('jobs1')->onDelete('cascade');
      $table->foreign('education_id')->references('id')->on('education')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs_criteria');
  }
}
