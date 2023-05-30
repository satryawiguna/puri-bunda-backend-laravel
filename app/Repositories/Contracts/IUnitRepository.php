<?php

namespace App\Repositories\Contracts;

use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IUnitRepository
{
    public function allSearchUnits(ListSearchDataRequest $request): Collection;

    public function allSearchPageUnits(ListSearchPageDataRequest $request): LengthAwarePaginator;
}
