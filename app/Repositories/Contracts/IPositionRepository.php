<?php

namespace App\Repositories\Contracts;

use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IPositionRepository
{
    public function allSearchPositions(ListSearchDataRequest $request): Collection;

    public function allSearchPagePositions(ListSearchPageDataRequest $request): LengthAwarePaginator;

    public function allCountPosition(): int;
}
