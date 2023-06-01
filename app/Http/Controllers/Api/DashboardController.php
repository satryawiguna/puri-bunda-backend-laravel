<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Dashboard\DashboardRequest;
use App\Http\Resources\Dashboard\TopTenUserByLoginResourceCollection;
use App\Services\Contracts\IDashboardService;

class DashboardController extends ApiBaseController
{
    public IDashboardService $_dashboardService;

    public function __construct(IDashboardService $dashboardService)
    {
        $this->_dashboardService = $dashboardService;
    }

    public function countTotalEmployee(DashboardRequest $request)
    {
        $totalEmployee = $this->_dashboardService->getTotalEmployee($request);

        if ($totalEmployee->isError()) {
            return $this->getErrorLatestJsonResponse($totalEmployee);
        }

        return $this->getIntegerJsonResponse($totalEmployee);
    }

    public function countTotalLogin(DashboardRequest $request)
    {
        $totalLogin = $this->_dashboardService->getTotalLogin($request);

        if ($totalLogin->isError()) {
            return $this->getErrorLatestJsonResponse($totalLogin);
        }

        return $this->getIntegerJsonResponse($totalLogin);
    }

    public function countTotalUnit()
    {
        $totalUnit = $this->_dashboardService->getTotalUnit();

        if ($totalUnit->isError()) {
            return $this->getErrorLatestJsonResponse($totalUnit);
        }

        return $this->getIntegerJsonResponse($totalUnit);
    }

    public function countTotalPosition()
    {
        $totalPosition = $this->_dashboardService->getTotalPosition();

        if ($totalPosition->isError()) {
            return $this->getErrorLatestJsonResponse($totalPosition);
        }

        return $this->getIntegerJsonResponse($totalPosition);
    }

    public function topTenUserByLogin(DashboardRequest $request)
    {
        $topTenUserByLogin = $this->_dashboardService->getTopTenUserLogin($request);

        if ($topTenUserByLogin->isError()) {
            return $this->getErrorLatestJsonResponse($topTenUserByLogin);
        }

        return $this->getListJsonResponse($topTenUserByLogin, TopTenUserByLoginResourceCollection::class);
    }
}
