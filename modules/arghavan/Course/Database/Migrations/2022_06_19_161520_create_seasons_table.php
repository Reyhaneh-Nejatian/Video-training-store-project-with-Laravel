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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->unsignedTinyInteger('number');
            $table->enum('confirmation_status',\arghavan\Course\Models\Season::$confirmationStatuses)
                ->default(\arghavan\Course\Models\Season::CONFIRMATION_STATUS_PENDING);
            $table->enum('status',\arghavan\Course\Models\Season::$statuses)
                ->default(\arghavan\Course\Models\Season::STATUS_OPENED);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
};
