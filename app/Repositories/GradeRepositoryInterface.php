<?php

namespace App\Repositories;

use App\Models\Grade;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface GradeRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Grade;

    public function create(array $data): Grade;

    public function update(Grade $grade, array $data): Grade;

    public function delete(Grade $grade): void;
}

