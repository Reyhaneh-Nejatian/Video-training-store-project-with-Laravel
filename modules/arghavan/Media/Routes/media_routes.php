<?php

use Illuminate\Support\Facades\Route;

Route::group([],function ($router){

    $router->get('/media/{media}/download',[\arghavan\Media\Http\Controllers\MediaController::class,'download'])->name('media.download');
});
