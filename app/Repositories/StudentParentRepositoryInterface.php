<?php

namespace App\Repositories;

use App\Models\StudentParent;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StudentParentRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?StudentParent;

    public function create(array $data): StudentParent;

    public function update(StudentParent $parent, array $data): StudentParent;

    public function delete(StudentParent $parent): void;
}

