<?php

namespace App\Providers;

use App\Repositories\Contracts\IPositionRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\PositionRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IPositionRepository::class, PositionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
