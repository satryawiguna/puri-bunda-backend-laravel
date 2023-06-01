<?php

namespace App\Repositories\Contracts;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Dashboard\DashboardRequest;
use Illuminate\Support\Collection;

interface IUserRepository
{
    public function register(RegisterRequest $request): BaseEntity;

    public function topTenUserByLogin(DashboardRequest $request): Collection;
}
