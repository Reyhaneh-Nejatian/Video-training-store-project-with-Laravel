<?php

use Illuminate\Support\Facades\Route;

Route::controller(\arghavan\Front\Http\Controllers\FrontCotroller::class)
    ->middleware(['web'])->group(function ($router){

        $router->get('/','index');
        $router->get('/c-{slug}','singleCourse')->name('singleCourse');
        $router->get('/tutors/{username}','singleTutor')->name('singleTutor');
    });

