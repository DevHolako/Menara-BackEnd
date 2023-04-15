<?php

namespace App\Providers;

use App\Http\Resources\API\Article\AricleResource;
use App\Http\Resources\API\Categorie\CategorieResource;
use App\Http\Resources\API\Client\CleintResource;
use App\Http\Resources\API\Devi\DeviResource;
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
        DeviResource::withoutWrapping();
        CleintResource::withoutWrapping();
        CategorieResource::withoutWrapping();
        AricleResource::withoutWrapping();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

    }
}
