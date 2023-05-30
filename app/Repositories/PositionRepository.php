<?php

namespace App\Repositories;

use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Models\Position;
use App\Repositories\Contracts\IPositionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PositionRepository extends BaseRepository implements IPositionRepository
{
    public function __construct(Position $position)
    {
        parent::__construct($position);
    }

    public function allSearchPositions(ListSearchDataRequest $request): Collection
    {
        $position = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $position = $position->whereRaw("(title LIKE ?)", $this->searchPositionByKeyword($keyword));
        }

        return $position->orderBy($request->order_by, $request->sort)
            ->get();
    }

    public function allSearchPagePositions(ListSearchPageDataRequest $request): LengthAwarePaginator
    {
        $position = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $position = $position->whereRaw("(title LIKE ?)", $this->searchPositionByKeyword($keyword));
        }

        return $position->orderBy($request->order_by, $request->sort)
            ->paginate($request->per_page, ['*'], 'page', $request->page);
    }

    private function searchPositionByKeyword(string $keyword) {
        return [
            'title' => "%" . $keyword . "%"
        ];
    }
}
