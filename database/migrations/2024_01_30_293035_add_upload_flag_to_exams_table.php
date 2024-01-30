<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
  {
    Schema::table('exams', function (Blueprint $table) {
      $table->dateTime('uploaded_at')->nullable();
      $table->longText('upload_response')->nullable();
    });
  }

  public function down()
  {
    Schema::table('exams', function (Blueprint $table) {
      $table->dropColumn(['uploaded_at', 'upload_response']);
    });
  }
};
