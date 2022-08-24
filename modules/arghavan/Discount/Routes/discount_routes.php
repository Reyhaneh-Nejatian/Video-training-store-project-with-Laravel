<?php

use Illuminate\Support\Facades\Route;


Route::namespace('arghavan\Discount\Http\Controllers')->middleware(['web','auth','verified'])
    ->group(function ($router){

        $router->get('/discounts',[
            "uses" => 'DiscountController@index',
            "as" => ('discounts.index')
        ]);

        $router->post('/discounts',[\arghavan\Discount\Http\Controllers\DiscountController::class,'store'])
            ->name('discounts.store');

        $router->delete('/discounts/{discount}',[\arghavan\Discount\Http\Controllers\DiscountController::class,'destroy'])
            ->name('discounts.destroy');

        $router->get('/discounts/{discount}/edit',[\arghavan\Discount\Http\Controllers\DiscountController::class,'edit'])
            ->name('discounts.edit');

        $router->patch('/discounts/{discount}',[\arghavan\Discount\Http\Controllers\DiscountController::class,'update'])
            ->name('discounts.update');


        $router->get('/discounts/{code}/{course}/check',[\arghavan\Discount\Http\Controllers\DiscountController::class,'check'])
            ->name('discounts.check')->middleware('throttle:6,1');

    });
