<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateResumeSkillTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('resume_skill', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('resume_id',100)->nullable();
      $table->string('skill_id',100)->nullable();
      $table->string('custom_skill', 100);

      $table->foreign('resume_id')->references('id')->on('resume')->onDelete('cascade');
      $table->foreign('skill_id')->references('id')->on('skill')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('resume_skill');
  }
}
