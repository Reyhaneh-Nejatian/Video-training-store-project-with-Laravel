<?php

use Illuminate\Support\Facades\Route;

Route::namespace('arghavan\Category\Http\Controllers')->middleware(['web','auth','verified'])
    ->group(function ($router){

        $router->resource('/categories',\arghavan\Category\Http\Controllers\CategoryConreoller::class);
    });


