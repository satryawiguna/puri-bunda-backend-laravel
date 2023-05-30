<?php

namespace App\Services\Contracts;

use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericListResponse;
use App\Core\Responses\GenericListSearchPageResponse;
use App\Core\Responses\GenericListSearchResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Http\Requests\Position\PositionStoreRequest;
use App\Http\Requests\Position\PositionUpdateRequest;
use App\Http\Requests\Unit\UnitStoreRequest;
use App\Http\Requests\Unit\UnitUpdateRequest;

interface IMasterService
{
    public function getAllPositions(ListDataRequest $request): GenericListResponse;

    public function getAllSearchPositions(ListSearchDataRequest $request): GenericListSearchResponse;

    public function getAllSearchPagePositions(ListSearchPageDataRequest $request): GenericListSearchPageResponse;

    public function getPosition(int $id): GenericObjectResponse;

    public function storePosition(PositionStoreRequest $request): GenericObjectResponse;

    public function updatePosition(int $id, PositionUpdateRequest $request): GenericObjectResponse;

    public function destroyPosition(int $id): BasicResponse;


    public function getAllUnits(ListDataRequest $request): GenericListResponse;

    public function getAllSearchUnits(ListSearchDataRequest $request): GenericListSearchResponse;

    public function getAllSearchPageUnits(ListSearchPageDataRequest $request): GenericListSearchPageResponse;

    public function getUnit(int $id): GenericObjectResponse;

    public function storeUnit(UnitStoreRequest $request): GenericObjectResponse;

    public function updateUnit(int $id, UnitUpdateRequest $request): GenericObjectResponse;

    public function destroyUnit(int $id): BasicResponse;
}
