<?php

namespace App\Repositories\Eloquent;

use App\Models\Grade;
use App\Repositories\GradeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GradeRepository implements GradeRepositoryInterface
{
    public function all(): Collection
    {
        return Grade::orderBy('name')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Grade::orderBy('name')->paginate($perPage);
    }

    public function find(int $id): ?Grade
    {
        return Grade::find($id);
    }

    public function create(array $data): Grade
    {
        return Grade::create($data);
    }

    public function update(Grade $grade, array $data): Grade
    {
        $grade->update($data);

        return $grade;
    }

    public function delete(Grade $grade): void
    {
        $grade->delete();
    }
}

