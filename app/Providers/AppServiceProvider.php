<?php

namespace App\Providers;

use App\Crud\Crud;
use App\Resources\UserResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Crud::class, function ($app) {
            return new Crud();
        });

        /** @var Crud $crud */
        $crud = app(Crud::class);

        $crud->resources([
            UserResource::class
        ])->boot();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
