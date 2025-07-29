<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsSalaryTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_salary', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs1_id',100)->nullable();
      $table->enum('type', ["main", "additional", "custom", ])->default('main');
      $table->double('salary', 20, 2)->default(0);

      $table->foreign('jobs1_id')->references('id')->on('jobs')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs_salary');
  }
}
