<?php

use Illuminate\Support\Facades\Route;

Route::group([],function ($router){

    $router->any('/payment/callback',[\arghavan\Payment\Http\Controllers\PaymentController::class,'callback'])
        ->name('payments.callback');

    $router->get('/payments',[
        "uses" => 'PaymentController@index',
        "as" => ('payments.index')
    ]);

    $router->get('/purchases',[
        "uses" => 'PaymentController@purchases',
        "as" => ('purchases.index')
    ]);
});
