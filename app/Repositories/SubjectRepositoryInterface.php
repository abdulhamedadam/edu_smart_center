<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SubjectRepositoryInterface
{
    public function all(): Collection;

    public function forGrade(int $gradeId): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Subject;

    public function create(array $data): Subject;

    public function update(Subject $subject, array $data): Subject;

    public function delete(Subject $subject): void;
}

