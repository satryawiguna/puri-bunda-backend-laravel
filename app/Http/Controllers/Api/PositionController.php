<?php

namespace App\Http\Controllers\Api;

use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\Position\PositionStoreRequest;
use App\Http\Requests\Position\PositionUpdateRequest;
use App\Http\Resources\Position\PositionResource;
use App\Http\Resources\Position\PositionResourceCollection;
use App\Services\Contracts\IMasterService;

class PositionController extends ApiBaseController
{
    public IMasterService $_masterService;

    public function __construct(IMasterService $masterService)
    {
        $this->_masterService = $masterService;
    }

    public function list(ListDataRequest $request)
    {
        $positions = $this->_masterService->getAllPositions($request);

        if ($positions->isError()) {
            return $this->getErrorLatestJsonResponse($positions);
        }

        return $this->getListJsonResponse($positions, PositionResourceCollection::class);
    }

    public function listSearch(ListSearchDataRequest $request)
    {
        $positions = $this->_masterService->getAllSearchPositions($request);

        if ($positions->isError()) {
            return $this->getErrorLatestJsonResponse($positions);
        }

        return $this->getListSearchJsonResponse($positions, PositionResourceCollection::class);
    }

    public function listSearchPage(ListSearchPageDataRequest $request)
    {
        $positions = $this->_masterService->getAllSearchPagePositions($request);

        if ($positions->isError()) {
            return $this->getErrorLatestJsonResponse($positions);
        }

        return $this->getListSearchPageJsonResponse($positions, PositionResourceCollection::class);
    }

    public function show(int $id)
    {
        $position = $this->_masterService->getPosition($id);

        if ($position->isError()) {
            return $this->getErrorLatestJsonResponse($position);
        }

        return $this->getObjectJsonResponse($position, PositionResource::class);
    }

    public function store(PositionStoreRequest $request)
    {
        $positionStoreResponse = $this->_masterService->storePosition($request);

        if ($positionStoreResponse->isError()) {
            return $this->getErrorLatestJsonResponse($positionStoreResponse);
        }

        return $this->getObjectJsonResponse($positionStoreResponse, PositionResource::class);
    }

    public function update(int $id, PositionUpdateRequest $request)
    {
        $positionUpdateResponse = $this->_masterService->updatePosition($id, $request);

        if ($positionUpdateResponse->isError()) {
            return $this->getErrorLatestJsonResponse($positionUpdateResponse);
        }

        return $this->getObjectJsonResponse($positionUpdateResponse, PositionResource::class);
    }

    public function destroy(int $id)
    {
        $positionDeleteResponse = $this->_masterService->destroyPosition($id);

        if ($positionDeleteResponse->isError()) {
            return $this->getErrorLatestJsonResponse($positionDeleteResponse);
        }

        return $this->getSuccessLatestJsonResponse($positionDeleteResponse);
    }
}
