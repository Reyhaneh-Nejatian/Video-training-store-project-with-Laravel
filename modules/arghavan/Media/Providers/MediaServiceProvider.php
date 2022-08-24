<?php


namespace arghavan\Media\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    protected $namespace = 'arghavan\Media\Http\Controllers';
    public function register()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../Routes/media_routes.php');
//
//        $this->loadViewsFrom(__DIR__.'/../Resources/Views','Media');
//
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->mergeConfigFrom(__DIR__.'/../Config/mediaFile.php','mediaFile');
    }

    public function boot(){

    }
}
