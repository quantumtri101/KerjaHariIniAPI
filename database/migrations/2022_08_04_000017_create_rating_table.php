<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateRatingTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('rating', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('user_id',100)->nullable();
      $table->string('jobs_application_id',100)->nullable();
      $table->string('staff_id',100)->nullable();
      $table->double('rating', 20, 10)->default(0);
      $table->string('review', 100)->nullable();

      $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
      $table->foreign('staff_id')->references('id')->on('user')->onDelete('cascade');
      $table->foreign('jobs_application_id')->references('id')->on('jobs_application')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('rating');
  }
}
