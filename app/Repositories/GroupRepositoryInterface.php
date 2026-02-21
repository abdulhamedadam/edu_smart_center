<?php

namespace App\Repositories;

use App\Models\Group;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface GroupRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator;

    public function allWithRelations(): Collection;

    public function find(int $id): ?Group;

    public function create(array $data): Group;

    public function update(Group $group, array $data): Group;

    public function delete(Group $group): void;
}
