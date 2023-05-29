<?php

namespace App\Core\Responses;

use Illuminate\Support\Collection;

class GenericListSearchResponse extends BasicResponse
{
    public Collection $dtoListSearch;

    public int $totalCount;

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getDtoListSearch(): Collection
    {
        return $this->dtoListSearch ?? new Collection();
    }
}
