<?php

namespace App\Services\Contracts;

use App\Core\Requests\ListDataRequest;
use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericListResponse;
use App\Core\Responses\GenericListSearchPageResponse;
use App\Core\Responses\GenericListSearchResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Http\Requests\Employee\EmployeeListSearchDataRequest;
use App\Http\Requests\Employee\EmployeeListSearchPageDataRequest;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;

interface IEmployeeService
{
    public function getAllEmployees(ListDataRequest $request): GenericListResponse;

    public function getAllSearchEmployees(EmployeeListSearchDataRequest $request): GenericListSearchResponse;

    public function getAllSearchPageEmployees(EmployeeListSearchPageDataRequest $request): GenericListSearchPageResponse;

    public function getAllCountEmployee(): GenericObjectResponse;

    public function getEmployee(string $id): GenericObjectResponse;

    public function storeEmployee(EmployeeStoreRequest $request): GenericObjectResponse;

    public function updateEmployee(int $id, EmployeeUpdateRequest $request): GenericObjectResponse;

    public function destroyEmployee(string $id): BasicResponse;
}
