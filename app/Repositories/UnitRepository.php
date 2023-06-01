<?php

namespace App\Repositories;

use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Repositories\Contracts\IUnitRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\Unit;

class UnitRepository extends BaseRepository implements IUnitRepository
{
    public function __construct(Unit $position)
    {
        parent::__construct($position);
    }

    public function allSearchUnits(ListSearchDataRequest $request): Collection
    {
        $unit = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $unit = $unit->whereRaw("(title LIKE ?)", $this->searchPositionByKeyword($keyword));
        }

        return $unit->orderBy($request->order_by, $request->sort)
            ->get();
    }

    public function allSearchPageUnits(ListSearchPageDataRequest $request): LengthAwarePaginator
    {
        $unit = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $unit = $unit->whereRaw("(title LIKE ?)", $this->searchUnitByKeyword($keyword));
        }

        return $unit->orderBy($request->order_by, $request->sort)
            ->paginate($request->per_page, ['*'], 'page', $request->page);
    }

    public function allCountUnit(): int
    {
        return $this->_model->get()
            ->count();
    }

    private function searchUnitByKeyword(string $keyword) {
        return [
            'title' => "%" . $keyword . "%"
        ];
    }
}
