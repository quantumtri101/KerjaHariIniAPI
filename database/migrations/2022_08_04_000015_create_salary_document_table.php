<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateSalaryDocumentTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('salary_document', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('jobs_application_id',100)->nullable();
      $table->string('file_name', 100)->nullable();

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
    Schema::dropIfExists('salary_document');
  }
}
