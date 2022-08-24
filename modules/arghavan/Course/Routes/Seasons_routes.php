<?php

use Illuminate\Support\Facades\Route;


Route::controller(\arghavan\Course\Http\Controllers\SeasonController::class)
    ->middleware(['web','auth','verified'])->group(function ($router){

        $router->patch('/seasons/{season}/accept','accept')->name('seasons.accept');
        $router->patch('/seasons/{season}/reject','reject')->name('seasons.reject');
        $router->post('/seasons/{season}','store')->name('seasons.store');
        $router->get('/seasons/{season}','edit')->name('seasons.edit');
        $router->patch('/seasons/{season}','update')->name('seasons.update');
        $router->delete('/seasons/{season}','destroy')->name('seasons.destroy');
        $router->patch('/seasons/{season}/lock','lock')->name('seasons.lock');
        $router->patch('/seasons/{season}/unlock','unlock')->name('seasons.unlock');
    });
