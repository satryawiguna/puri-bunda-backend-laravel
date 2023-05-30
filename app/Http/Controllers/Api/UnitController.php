<?php

namespace App\Http\Controllers\Api;

use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\Unit\UnitStoreRequest;
use App\Http\Requests\Unit\UnitUpdateRequest;
use App\Http\Resources\Unit\UnitResource;
use App\Http\Resources\Unit\UnitResourceCollection;
use App\Services\Contracts\IMasterService;

class UnitController extends ApiBaseController
{
    public IMasterService $_masterService;

    public function __construct(IMasterService $masterService)
    {
        $this->_masterService = $masterService;
    }

    public function list(ListDataRequest $request)
    {
        $units = $this->_masterService->getAllUnits($request);

        if ($units->isError()) {
            return $this->getErrorLatestJsonResponse($units);
        }

        return $this->getListJsonResponse($units, UnitResourceCollection::class);
    }

    public function listSearch(ListSearchDataRequest $request)
    {
        $units = $this->_masterService->getAllSearchUnits($request);

        if ($units->isError()) {
            return $this->getErrorLatestJsonResponse($units);
        }

        return $this->getListSearchJsonResponse($units, UnitResourceCollection::class);
    }

    public function listSearchPage(ListSearchPageDataRequest $request)
    {
        $units = $this->_masterService->getAllSearchPageUnits($request);

        if ($units->isError()) {
            return $this->getErrorLatestJsonResponse($units);
        }

        return $this->getListSearchPageJsonResponse($units, UnitResourceCollection::class);
    }

    public function show(int $id)
    {
        $unit = $this->_masterService->getUnit($id);

        if ($unit->isError()) {
            return $this->getErrorLatestJsonResponse($unit);
        }

        return $this->getObjectJsonResponse($unit, UnitResource::class);
    }

    public function store(UnitStoreRequest $request)
    {
        $unitStoreResponse = $this->_masterService->storeUnit($request);

        if ($unitStoreResponse->isError()) {
            return $this->getErrorLatestJsonResponse($unitStoreResponse);
        }

        return $this->getObjectJsonResponse($unitStoreResponse, UnitResource::class);
    }

    public function update(int $id, UnitUpdateRequest $request)
    {
        $unitUpdateResponse = $this->_masterService->updateUnit($id, $request);

        if ($unitUpdateResponse->isError()) {
            return $this->getErrorLatestJsonResponse($unitUpdateResponse);
        }

        return $this->getObjectJsonResponse($unitUpdateResponse, UnitResource::class);
    }

    public function destroy(int $id)
    {
        $unitDeleteResponse = $this->_masterService->destroyUnit($id);

        if ($unitDeleteResponse->isError()) {
            return $this->getErrorLatestJsonResponse($unitDeleteResponse);
        }

        return $this->getSuccessLatestJsonResponse($unitDeleteResponse);
    }
}
