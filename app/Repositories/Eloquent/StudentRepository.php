<?php

namespace App\Repositories\Eloquent;

use App\Models\Student;
use App\Repositories\StudentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StudentRepository implements StudentRepositoryInterface
{
    public function allWithRelations(?string $search = null): Collection
    {
        $query = Student::with(['grade', 'parent'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $search = trim($search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('student_code', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        return $query->get();
    }

    public function paginateWithRelations(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = Student::with(['grade', 'parent'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $search = trim($search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('student_code', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Student
    {
        return Student::find($id);
    }

    public function create(array $data): Student
    {
        return Student::create($data);
    }

    public function update(Student $student, array $data): Student
    {
        $student->update($data);

        return $student;
    }

    public function delete(Student $student): void
    {
        $student->delete();
    }
}
