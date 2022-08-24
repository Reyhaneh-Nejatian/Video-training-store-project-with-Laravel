<?php

use arghavan\Comment\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::namespace('arghavan\Comment\Http\Controllers')->middleware(['web','auth','verified'])
    ->group(function ($router){

        $router->resource('/comments',\arghavan\Comment\Http\Controllers\CommentController::class);
        $router->patch('/comments/{comment}/accept',[CommentController::class,'accept'])->name('comments.accept');
        $router->patch('/comments/{comment}/reject',[CommentController::class,'reject'])->name('comments.reject');

        $router->get('/comments',[
            "uses" => 'CommentController@index',
            "as" => ('comments.index')
        ]);
    });
