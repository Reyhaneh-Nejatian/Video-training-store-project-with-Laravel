<?php

use Illuminate\Support\Facades\Route;


Route::namespace('arghavan\Common\Http\Controllers')
    ->middleware(['web','auth','verified'])->group(function ($router){

        $router->get('/notifications/mark-as-read',[\arghavan\Common\Http\Controllers\NotificationController::class,'markAllAsRead'])
            ->name('notifications.markAllAsRead');
    });
