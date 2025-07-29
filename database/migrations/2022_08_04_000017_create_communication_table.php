<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateCommunicationTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('communication', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('communication_type_id',100)->nullable();
      $table->string('communication_method_id',100)->nullable();
      $table->string('user_id',100)->nullable();
      $table->string('type_id',100)->nullable();
      $table->text('title')->nullable();
      $table->longText('detail')->nullable();
      $table->dateTime('sent_for')->nullable();
      $table->dateTime('sent_at')->nullable();
      $table->enum('status', ["pending", 'sent', 'canceled'])->default('pending');

      $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
      $table->foreign('type_id')->references('id')->on('type')->onDelete('cascade');
      $table->foreign('communication_type_id')->references('id')->on('communication_type')->onDelete('cascade');
      $table->foreign('communication_method_id')->references('id')->on('communication_method')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('communication');
  }
}
