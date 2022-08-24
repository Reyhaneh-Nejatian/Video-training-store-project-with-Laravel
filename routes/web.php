<?php

use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




//تست ایمیل وریفای کد
//Routes::get('/test',function (){
//    return new \arghavan\User\Mail\VerifyCodeMail();
//});


Route::get('/test', function () {
//    \Spatie\Permission\Models\Permission::create(['name' => 'manage role_permissions']);
    auth()->user()->givePermissionTo(\arghavan\RolePermissions\Models\Permission::PERMISSION_SUPER_ADMIN);
    return auth()->user()->permissions;
});






//Routes::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
