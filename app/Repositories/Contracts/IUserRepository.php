<?php

namespace App\Repositories\Contracts;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\User\RegisterRequest;

interface IUserRepository
{
    public function register(RegisterRequest $request): BaseEntity;
}
