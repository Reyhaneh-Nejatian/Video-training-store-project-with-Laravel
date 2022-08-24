<?php

use Illuminate\Support\Facades\Route;

Route::namespace('arghavan\User\Http\Controllers')->middleware(['web', 'auth'])->group(function ($router){

    $router->post('/users/{user}/add/role',[arghavan\User\Http\Controllers\UserController::class,'addRole'])->name('users.addRole');
    $router->delete('/users/{user}/remove/{role}/role',[arghavan\User\Http\Controllers\UserController::class,'removeRole'])->name('users.removeRole');
    $router->patch('/users/{user}/manualVerify',[arghavan\User\Http\Controllers\UserController::class,'manualVerify'])->name('users.manualVerify');
    $router->post('/users/photo',[arghavan\User\Http\Controllers\UserController::class,'updatePhoto'])->name('users.photo');
    $router->get('/edit-profile',["uses" => "UserController@profile", "as" => "users.profile"]);
    $router->post('/edit-profile',[arghavan\User\Http\Controllers\UserController::class,'updateProfile'])->name('users.updateProfile');
    $router->get('/users/{user}/info',[arghavan\User\Http\Controllers\UserController::class,'info'])->name('users.info');
    $router->resource('/users',arghavan\User\Http\Controllers\UserController::class);
});

Route::namespace('arghavan\User\Http\Controllers')->middleware('web')
    ->group(function ($router){
//    Auth::routes(['verify' => true]);

        $router->post('/email/verify',[arghavan\User\Http\Controllers\Auth\VerificationController::class,'verify'])->name('verification.verify');
        $router->post('/email/resend',[arghavan\User\Http\Controllers\Auth\VerificationController::class,'resend'])->name('verification.resend');
        $router->get('/email/verify',[arghavan\User\Http\Controllers\Auth\VerificationController::class,'show'])->name('verification.notice');

    //login
        $router->get('/login', [arghavan\User\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
        $router->post('/login',[arghavan\User\Http\Controllers\Auth\LoginController::class,'login']);

    //logout
        $router->any('/logout',[arghavan\User\Http\Controllers\Auth\LoginController::class,'logout'])->name('logout');

    //reset password
        $router->get('/password/reset',[arghavan\User\Http\Controllers\Auth\ForgotPasswordController::class,'showVerifyCodeRequestForm'])->name('password.request');
        $router->get('/password/reset/send',[arghavan\User\Http\Controllers\Auth\ForgotPasswordController::class,'sendVerifyCodeEmail'])
            ->name('password.sendVerifyCodeEmail')->middleware('throttle:5,30');

        $router->post('/password/reset/check-verify-code',[arghavan\User\Http\Controllers\Auth\ForgotPasswordController::class,'checkVerifyCode'])
        ->name('password.checkVerifyCode')
        ->middleware('throttle:5,1');  //در هر 1 دقیقه فقط 5 بار کد وارد کند در غیر این صورت بن میشود

    $router->get('/password/change',[arghavan\User\Http\Controllers\Auth\ResetPasswordController::class,'showResetForm'])
        ->name('password.showResetForm')->middleware('auth');

    $router->post('/password/change',[arghavan\User\Http\Controllers\Auth\ResetPasswordController::class,'reset'])->name('password.update');

    //register
    $router->get('/register',[arghavan\User\Http\Controllers\Auth\RegisterController::class,'showRegistrationForm'])->name('register');
    $router->post('/register',[arghavan\User\Http\Controllers\Auth\RegisterController::class,'register']);

});
