<?php

use Illuminate\Support\Facades\Route;


Route::controller(\arghavan\Course\Http\Controllers\CourseController::class)
    ->middleware(['web','auth','verified'])->group(function ($router){

        $router->resource('/courses',\arghavan\Course\Http\Controllers\CourseController::class);
        $router->patch('/courses/{course}/accept','accept')->name('courses.accept');
        $router->patch('/courses/{course}/reject','reject')->name('courses.reject');
        $router->patch('/courses/{course}/lock','lock')->name('courses.lock');
        $router->get('/courses/{course}/details','details')->name('courses.details');
        $router->post('/courses/{course}/buy','buy')->name('courses.buy');
        $router->get('/courses/{course}/download-links','downloadLinks')->name('courses.downloadLinks');
    });
