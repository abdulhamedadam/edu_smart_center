<?php

namespace App\Repositories\Eloquent;

use App\Models\Teacher;
use App\Repositories\TeacherRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function allWithRelations(): Collection
    {
        return Teacher::with(['subjects.grade', 'groups'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator
    {
        return Teacher::with(['subjects.grade', 'groups'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function find(int $id): ?Teacher
    {
        return Teacher::with(['subjects.grade', 'groups'])->find($id);
    }

    public function create(array $data): Teacher
    {
        return Teacher::create($data);
    }

    public function update(Teacher $teacher, array $data): Teacher
    {
        $teacher->update($data);

        return $teacher;
    }

    public function delete(Teacher $teacher): void
    {
        $teacher->delete();
    }
}
