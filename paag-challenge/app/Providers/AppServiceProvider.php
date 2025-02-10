<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\{
    CacheService,
    RequestService,
};
use App\Repositories\{
    CacheRepository,
    RequestRepository,
};
use App\Contracts\Services\{
    CacheServiceInterface,
    RequestServiceInterface,
};
use App\Contracts\Repositories\{
    CacheRepositoryInterface,
    RequestRepositoryInterface,
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->bind(RequestServiceInterface::class, RequestService::class);

        $this->app->bind(CacheRepositoryInterface::class, CacheRepository::class);
        $this->app->bind(RequestRepositoryInterface::class, RequestRepository::class);
    }
}
