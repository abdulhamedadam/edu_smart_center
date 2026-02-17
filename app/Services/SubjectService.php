<?php

namespace App\Services;

use App\Models\Subject;
use App\Repositories\SubjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SubjectService
{
    public function __construct(
        protected SubjectRepositoryInterface $subjects
    ) {
    }

    public function list(): LengthAwarePaginator
    {
        return $this->subjects->paginate();
    }

    public function options(): Collection
    {
        return $this->subjects->all();
    }

    public function optionsForGrade(int $gradeId): Collection
    {
        return $this->subjects->forGrade($gradeId);
    }

    public function create(array $data): Subject
    {
        return $this->subjects->create($data);
    }

    public function update(Subject $subject, array $data): Subject
    {
        return $this->subjects->update($subject, $data);
    }

    public function delete(Subject $subject): void
    {
        $this->subjects->delete($subject);
    }
}

