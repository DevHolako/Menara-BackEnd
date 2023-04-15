<?php

namespace App\Providers;

use App\Http\Resources\Api\Permission\PermissionResource;
use App\Http\Resources\Api\Role\RoleResource;
use App\Http\Resources\Api\User\UserResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        UserResource::withoutWrapping();
        RoleResource::withoutWrapping();
        PermissionResource::withoutWrapping();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

    }
}
