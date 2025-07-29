<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateCheckLogDocumentTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('check_log_document', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('check_log_id',100)->nullable();
      $table->string('file_name', 100)->nullable();

      $table->foreign('check_log_id')->references('id')->on('check_log')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('check_log_document');
  }
}
