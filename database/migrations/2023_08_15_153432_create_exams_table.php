<?php

use App\Enums\ExamStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('exams', function (Blueprint $table) {
      $table->id();

      $table->string('platform');
      $table->string('reference')->nullable();
      $table->unsignedBigInteger('event_id')->nullable();
      $table
        ->string('exam_no')
        ->unique()
        ->index();

      $table->float('duration');
      $table->float('time_remaining')->default(0);
      $table->dateTime('start_time')->nullable(true);
      $table->dateTime('pause_time')->nullable(true);
      $table->dateTime('end_time')->nullable(true);
      $table->float('score')->nullable(true);
      $table->integer('num_of_questions', false, true)->nullable(true);
      $table->string('status')->default(ExamStatus::Pending->value);
      $table->mediumText('attempts')->nullable();
      $table->text('meta')->nullable();
      $table->boolean('show_result')->default(false);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('exams');
  }
};
