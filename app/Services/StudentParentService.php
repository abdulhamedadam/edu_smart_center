<?php

namespace App\Services;

use App\Models\StudentParent;
use App\Repositories\StudentParentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StudentParentService
{
    public function __construct(
        protected StudentParentRepositoryInterface $parents
    ) {
    }

    public function list(): LengthAwarePaginator
    {
        return $this->parents->paginate();
    }

    public function all(): Collection
    {
        return $this->parents->all();
    }

    public function options(): Collection
    {
        return $this->parents->all();
    }

    public function create(array $data): StudentParent
    {
        return $this->parents->create($data);
    }

    public function update(StudentParent $parent, array $data): StudentParent
    {
        return $this->parents->update($parent, $data);
    }

    public function delete(StudentParent $parent): void
    {
        $this->parents->delete($parent);
    }
}
