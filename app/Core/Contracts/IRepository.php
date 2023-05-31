<?php

namespace App\Core\Contracts;

use App\Core\Entities\BaseEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

interface IRepository
{
    public function all(string $order = "id", string $sort = "asc"): Collection;

    public function findById(int | string $id): BaseEntity | null;

    public function findOrNew(array $data): BaseEntity;

    public function create(FormRequest $request): BaseEntity;

    public function update(FormRequest $request): BaseEntity | null;

    public function delete(int | string $id): BaseEntity | null;
}
