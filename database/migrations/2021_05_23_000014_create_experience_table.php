<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateExperienceTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('experience', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('resume_id',100)->nullable();
      $table->string('city_id',100)->nullable();
      $table->string('name', 100);
      $table->double('start_year', 20, 2)->default(0);
      $table->double('end_year', 20, 2)->default(0);
      $table->text('corporation')->nullable();
      $table->longText('description')->nullable();

      $table->foreign('resume_id')->references('id')->on('resume')->onDelete('cascade');
      $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('experience');
  }
}
