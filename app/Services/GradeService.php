<?php

namespace App\Services;

use App\Models\Grade;
use App\Repositories\GradeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GradeService
{
    public function __construct(
        protected GradeRepositoryInterface $grades
    ) {
    }

    public function list(): LengthAwarePaginator
    {
        return $this->grades->paginate();
    }

    public function options(): Collection
    {
        return $this->grades->all();
    }

    public function create(array $data): Grade
    {
        return $this->grades->create($data);
    }

    public function update(Grade $grade, array $data): Grade
    {
        return $this->grades->update($grade, $data);
    }

    public function delete(Grade $grade): void
    {
        $this->grades->delete($grade);
    }
}

