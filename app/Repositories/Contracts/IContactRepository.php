<?php

namespace App\Repositories\Contracts;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\Dashboard\DashboardRequest;
use App\Http\Requests\Employee\EmployeeListSearchDataRequest;
use App\Http\Requests\Employee\EmployeeListSearchPageDataRequest;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IContactRepository
{
    public function allEmployees(string $orderBy = "id", string $sort = "asc"): Collection;

    public function allSearchEmployees(EmployeeListSearchDataRequest $request): Collection;

    public function allSearchPageEmployees(EmployeeListSearchPageDataRequest $request): LengthAwarePaginator;

    public function allCountEmployee(DashboardRequest $request): int;

    public function createEmployee(EmployeeStoreRequest $request, int $unitId, array $positionIds): BaseEntity;

    public function updateEmployee(EmployeeUpdateRequest $request, int $unitId, array $positionIds): BaseEntity | null;
}
