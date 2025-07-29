<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateGeneralQuizOptionTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('general_quiz_option', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('general_quiz_question_id',100)->nullable();
      $table->string('option',100);
      $table->boolean('is_publish')->default(1);
      $table->boolean('is_true')->default(0);

      $table->foreign('general_quiz_question_id')->references('id')->on('general_quiz_question')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('general_quiz_option');
  }
}
