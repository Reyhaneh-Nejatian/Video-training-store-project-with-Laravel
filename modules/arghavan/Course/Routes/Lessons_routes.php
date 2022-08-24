<?php

use Illuminate\Support\Facades\Route;


Route::controller(\arghavan\Course\Http\Controllers\LessonController::class)
    ->middleware(['web','auth','verified'])->group(function ($router){

        $router->get('/courses/{course}/lessons/create','create')->name('lessons.create');
        $router->post('/courses/{course}/lessons','store')->name('lessons.store');
        $router->get('/courses/{course}/lessons/{lesson}/edit','edit')->name('lessons.edit');
        $router->patch('/courses/{course}/lessons/{lesson}/edit','update')->name('lessons.update');
        $router->delete('/courses/{course}/lessons/{lesson}','destroy')->name('lessons.destroy');
        $router->delete('/courses/{course}/lessons/','destroyMultiple')->name('lessons.destroyMultiple');
        $router->patch('/lessons/{lesson}/accept','accept')->name('lessons.accept');
        $router->patch('/courses/{course}/lessons/accept-all','acceptAll')->name('lessons.acceptAll');
        $router->patch('/courses/{course}/lessons/accept-selected','acceptMultiple')->name('lessons.acceptMultiple');
        $router->patch('/lessons/{lesson}/reject','reject')->name('lessons.reject');
        $router->patch('/courses/{course}/lessons/reject-selected','rejectMultiple')->name('lessons.rejectMultiple');
        $router->patch('/lessons/{lesson}/lock','lock')->name('lessons.lock');
        $router->patch('/lessons/{lesson}/unlock','unlock')->name('lessons.unlock');
    });

