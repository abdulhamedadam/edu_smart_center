<?php

namespace App\Repositories\Eloquent;

use App\Models\StudentParent;
use App\Repositories\StudentParentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StudentParentRepository implements StudentParentRepositoryInterface
{
    public function all(): Collection
    {
        return StudentParent::orderBy('name')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return StudentParent::orderBy('name')->paginate($perPage);
    }

    public function find(int $id): ?StudentParent
    {
        return StudentParent::find($id);
    }

    public function create(array $data): StudentParent
    {
        return StudentParent::create($data);
    }

    public function update(StudentParent $parent, array $data): StudentParent
    {
        $parent->update($data);

        return $parent;
    }

    public function delete(StudentParent $parent): void
    {
        $parent->delete();
    }
}

