<?php


use Illuminate\Support\Facades\Route;

Route::namespace('arghavan\Slider\Http\Controllers')->middleware(['web','auth','verified'])
    ->group(function ($router){

        $router->resource('/slides',\arghavan\Slider\Http\Controllers\SlideControllers::class);

    });
