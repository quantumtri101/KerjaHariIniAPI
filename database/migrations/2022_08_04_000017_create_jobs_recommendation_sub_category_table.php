<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsRecommendationSubCategoryTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_recommendation_sub_category', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs_recommendation_id',100)->nullable();
      $table->string('sub_category_id',100)->nullable();

      $table->foreign('jobs_recommendation_id')->references('id')->on('jobs_recommendation')->onDelete('cascade');
      $table->foreign('sub_category_id')->references('id')->on('sub_category')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs_recommendation_sub_category');
  }
}
