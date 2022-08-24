<?php

namespace arghavan\Slider\Providers;


use arghavan\RolePermissions\Models\Permission;
use Illuminate\Support\ServiceProvider;

class SliderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Route/slider_routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/Views','Sliders');
    }

    public function boot()
    {
        config()->set('sidebar.items.slider', [
            "icon" => "i-courses",
            "title" => "اسلایدر",
            "url" => route('slides.index'),
            "permission" => [
                Permission::PERMISSION_MANAGE_SLIDES,
            ]
        ]);
    }
}
