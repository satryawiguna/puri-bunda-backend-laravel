<?php

namespace App\Services\Contracts;

use App\Core\Responses\GenericListResponse;
use App\Core\Responses\IntegerResponse;
use App\Http\Requests\Dashboard\DashboardRequest;

interface IDashboardService
{
    public function getTotalEmployee(DashboardRequest $request): IntegerResponse;

    public function getTotalLogin(DashboardRequest $request): IntegerResponse;

    public function getTotalUnit(): IntegerResponse;

    public function getTotalPosition(): IntegerResponse;

    public function getTopTenUserLogin(DashboardRequest $request): GenericListResponse;
}
