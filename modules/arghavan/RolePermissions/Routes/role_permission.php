<?php

use Illuminate\Support\Facades\Route;

Route::namespace('arghavan\RolePermissions\Http\Controllers')->middleware(['web','auth','verified'])
    ->group(function ($router){

        $router->resource('/role-permissions',\arghavan\RolePermissions\Http\Controllers\RolePermissionConreoller::class)
            ->middleware('permission:manage role_permissions');

    });
