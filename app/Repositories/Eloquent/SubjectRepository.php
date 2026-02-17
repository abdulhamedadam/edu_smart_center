<?php

namespace App\Repositories\Eloquent;

use App\Models\Subject;
use App\Repositories\SubjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function all(): Collection
    {
        return Subject::with('grade')->orderBy('name')->get();
    }

    public function forGrade(int $gradeId): Collection
    {
        return Subject::where('grade_id', $gradeId)->orderBy('name')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Subject::with('grade')->orderBy('name')->paginate($perPage);
    }

    public function find(int $id): ?Subject
    {
        return Subject::find($id);
    }

    public function create(array $data): Subject
    {
        return Subject::create($data);
    }

    public function update(Subject $subject, array $data): Subject
    {
        $subject->update($data);

        return $subject;
    }

    public function delete(Subject $subject): void
    {
        $subject->delete();
    }
}

