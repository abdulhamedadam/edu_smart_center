<?php

namespace App\Repositories\Eloquent;

use App\Models\Group;
use App\Repositories\GroupRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GroupRepository implements GroupRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator
    {
        return Group::with(['grade', 'subject'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function allWithRelations(): Collection
    {
        return Group::with(['grade', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function find(int $id): ?Group
    {
        return Group::find($id);
    }

    public function create(array $data): Group
    {
        return Group::create($data);
    }

    public function update(Group $group, array $data): Group
    {
        $group->update($data);

        return $group;
    }

    public function delete(Group $group): void
    {
        $group->delete();
    }
}
