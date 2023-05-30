<?php

namespace App\Services\Contracts;

use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

interface IUserService
{
    public function register(RegisterRequest $request): GenericObjectResponse;

    public function login(LoginRequest $request): GenericObjectResponse;

    public function logout(): BasicResponse;
}
