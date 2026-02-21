<?php

namespace App\Repositories;

use App\Models\Teacher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TeacherRepositoryInterface
{
    public function allWithRelations(): Collection;

    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Teacher;

    public function create(array $data): Teacher;

    public function update(Teacher $teacher, array $data): Teacher;

    public function delete(Teacher $teacher): void;
}
