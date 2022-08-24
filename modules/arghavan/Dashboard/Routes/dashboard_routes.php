<?php

use Illuminate\Support\Facades\Route;

Route::namespace('arghavan\Dashboard\Http\Controllers')->middleware(['web','auth','verified'])
    ->group(function ($router){

        $router->get('/home', [\arghavan\Dashboard\Http\Controllers\DashboardConreoller::class, 'home'])->name('home');
    });


