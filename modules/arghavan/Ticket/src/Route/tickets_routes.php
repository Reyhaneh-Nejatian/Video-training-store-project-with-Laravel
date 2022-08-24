<?php

use arghavan\Ticket\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::namespace('arghavan\Ticket\Http\Controllers')->middleware(['web','auth','verified'])
    ->group(function ($router){

        $router->resource('/tickets',\arghavan\Ticket\Http\Controllers\TicketController::class);

        $router->post('/tickets/{ticket}/reply',[TicketController::class,'reply'])->name('tickets.reply');
        $router->get('/tickets/{ticket}/close',[TicketController::class,'close'])->name('tickets.close');
    });
