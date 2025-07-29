<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateGeneralQuizAnswerTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('general_quiz_answer', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('general_quiz_option_id',100)->nullable();
      $table->string('general_quiz_result_id',100)->nullable();

      $table->foreign('general_quiz_option_id')->references('id')->on('general_quiz_option')->onDelete('cascade');
      $table->foreign('general_quiz_result_id')->references('id')->on('general_quiz_result')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('general_quiz_answer');
  }
}
