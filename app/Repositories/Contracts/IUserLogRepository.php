<?php

namespace App\Repositories\Contracts;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\Dashboard\DashboardRequest;
use App\Http\Requests\UserLog\UserLogStoreRequest;

interface IUserLogRepository
{
    public function createUserLog(UserLogStoreRequest $request): BaseEntity;

    public function allCountUserLog(DashboardRequest $request): int;
}
