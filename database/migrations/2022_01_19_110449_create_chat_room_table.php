<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateChatRoomTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('chat_room', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('chat_id',100)->nullable();
      $table->string('sender_id',100)->nullable();
      $table->string('receiver_id',100)->nullable();
      $table->string('message',100)->nullable();
      $table->string('file_name',100)->nullable();
      $table->dateTime('read_at')->nullable();

      $table->foreign('chat_id')->references('id')->on('chat')->onDelete('cascade');
      $table->foreign('sender_id')->references('id')->on('user')->onDelete('cascade');
      $table->foreign('receiver_id')->references('id')->on('user')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('chat_room');
  }
}
