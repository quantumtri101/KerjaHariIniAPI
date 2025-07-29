<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobsBriefingTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs_briefing', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs1_id',100)->nullable();
      $table->string('pic_name',100)->nullable();
      $table->string('pic_phone',100)->nullable();
      $table->text('schedule')->nullable();
      $table->text('location')->nullable();
      $table->longText('notes')->nullable();

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
    Schema::dropIfExists('jobs_briefing');
  }
}
