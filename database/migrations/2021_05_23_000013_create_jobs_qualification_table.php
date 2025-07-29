<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsQualificationTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_qualification', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs1_id',100)->nullable();
      $table->string('name', 100);
      $table->boolean('is_publish')->default(0);

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
    Schema::dropIfExists('jobs_qualification');
  }
}
