<?php

namespace App\Providers;

use App\Services\Contracts\IDashboardService;
use App\Services\Contracts\IEmployeeService;
use App\Services\Contracts\IMasterService;
use App\Services\Contracts\IUserService;
use App\Services\DashboardService;
use App\Services\EmployeeService;
use App\Services\MasterService;
use App\Services\UserService;
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
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IMasterService::class, MasterService::class);
        $this->app->bind(IEmployeeService::class, EmployeeService::class);
        $this->app->bind(IDashboardService::class, DashboardService::class);
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
