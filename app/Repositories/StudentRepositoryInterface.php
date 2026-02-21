<?php

namespace App\Repositories;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StudentRepositoryInterface
{
    public function allWithRelations(?string $search = null): Collection;

    public function paginateWithRelations(int $perPage = 15, ?string $search = null): LengthAwarePaginator;

    public function find(int $id): ?Student;

    public function create(array $data): Student;

    public function update(Student $student, array $data): Student;

    public function delete(Student $student): void;
}
