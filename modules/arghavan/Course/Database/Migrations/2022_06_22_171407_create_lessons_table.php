<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('season_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('media_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->unsignedTinyInteger('time')->nullable();
            $table->unsignedInteger('number')->nullable();
            $table->boolean('is_free')->default(false);
            $table->longText('body')->nullable();
            $table->enum('confirmation_status',\arghavan\Course\Models\Lesson::$confirmationStatuses)
                ->default(\arghavan\Course\Models\Lesson::CONFIRMATION_STATUS_PENDING);
            $table->enum('status',\arghavan\Course\Models\Lesson::$statuses)
                ->default(\arghavan\Course\Models\Lesson::STATUS_OPENED);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};
