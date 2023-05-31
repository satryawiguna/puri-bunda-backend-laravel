<?php

namespace App\Http\Controllers\Api;

use App\Core\Requests\ListDataRequest;
use App\Http\Requests\Employee\EmployeeListSearchDataRequest;
use App\Http\Requests\Employee\EmployeeListSearchPageDataRequest;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Employee\EmployeeResourceCollection;
use App\Services\Contracts\IEmployeeService;

class EmployeeController extends ApiBaseController
{
    public IEmployeeService $_employeeService;

    public function __construct(IEmployeeService $employeeService)
    {
        $this->_employeeService = $employeeService;
    }

    public function list(ListDataRequest $request)
    {
        $employees = $this->_employeeService->getAllEmployees($request);

        if ($employees->isError()) {
            return $this->getErrorLatestJsonResponse($employees);
        }

        return $this->getListJsonResponse($employees, EmployeeResourceCollection::class);
    }

    public function listSearch(EmployeeListSearchDataRequest $request)
    {
        $employees = $this->_employeeService->getAllSearchEmployees($request);

        if ($employees->isError()) {
            return $this->getErrorLatestJsonResponse($employees);
        }

        return $this->getListSearchJsonResponse($employees, EmployeeResourceCollection::class);
    }

    public function listSearchPage(EmployeeListSearchPageDataRequest $request)
    {
        $employees = $this->_employeeService->getAllSearchPageEmployees($request);

        if ($employees->isError()) {
            return $this->getErrorLatestJsonResponse($employees);
        }

        return $this->getListSearchPageJsonResponse($employees, EmployeeResourceCollection::class);
    }

    public function show(string $id)
    {
        $employee = $this->_employeeService->getEmployee($id);

        if ($employee->isError()) {
            return $this->getErrorLatestJsonResponse($employee);
        }

        return $this->getObjectJsonResponse($employee, EmployeeResource::class);
    }

    public function store(EmployeeStoreRequest $request)
    {
        $employeeStoreResponse = $this->_employeeService->storeEmployee($request);

        if ($employeeStoreResponse->isError()) {
            return $this->getErrorLatestJsonResponse($employeeStoreResponse);
        }

        return $this->getObjectJsonResponse($employeeStoreResponse, EmployeeResource::class);
    }

    public function update(int $id, EmployeeUpdateRequest $request)
    {
        $employeeUpdateResponse = $this->_employeeService->updateEmployee($id, $request);

        if ($employeeUpdateResponse->isError()) {
            return $this->getErrorLatestJsonResponse($employeeUpdateResponse);
        }

        return $this->getObjectJsonResponse($employeeUpdateResponse, EmployeeResource::class);
    }

    public function destroy(string $id)
    {
        $employeeDeleteResponse = $this->_employeeService->destroyEmployee($id);

        if ($employeeDeleteResponse->isError()) {
            return $this->getErrorLatestJsonResponse($employeeDeleteResponse);
        }

        return $this->getSuccessLatestJsonResponse($employeeDeleteResponse);
    }
}
