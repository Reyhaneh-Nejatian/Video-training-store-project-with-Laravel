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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('banner_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->float('priority')->nullable();
            $table->string('price', 10);
            $table->string('percent', 5);
            $table->enum('type', \arghavan\Course\Models\Course::$types);
            $table->enum('status', \arghavan\Course\Models\Course::$statuses);
            $table->enum('confirmation_status', \arghavan\Course\Models\Course::$confirmationStatuses);
            $table->longText('body')->nullable();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('SET NULL');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('banner_id')->references('id')->on('media')->onDelete('SET NULL');
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
        Schema::dropIfExists('courses');
    }
};
