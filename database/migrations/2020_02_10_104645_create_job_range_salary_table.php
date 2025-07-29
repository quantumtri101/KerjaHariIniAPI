<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobRangeSalaryTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_range_salary', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->double('min_salary', 10, 2)->default(0);
      $table->double('max_salary', 10, 2)->default(0);
      $table->boolean('is_publish')->default(1);
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs_range_salary');
  }
}
