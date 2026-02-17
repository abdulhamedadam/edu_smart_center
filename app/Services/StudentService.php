<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentParent;
use App\Repositories\StudentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    public function __construct(
        protected StudentRepositoryInterface $students
    ) {
    }

    public function list(): LengthAwarePaginator
    {
        return $this->students->paginateWithRelations();
    }

    public function create(array $data): Student
    {
        $data = $this->prepareData($data);

        return $this->students->create($data);
    }

    public function update(Student $student, array $data): Student
    {
        $data = $this->prepareData($data, $student);

        return $this->students->update($student, $data);
    }

    public function delete(Student $student): void
    {
        if ($student->avatar_path) {
            Storage::disk('public')->delete($student->avatar_path);
        }

        $this->students->delete($student);
    }

    protected function prepareData(array $data, ?Student $student = null): array
    {
        if (! isset($data['student_code'])) {
            $data['student_code'] = $student?->student_code ?? $this->generateCode();
        }

        if (isset($data['parent_id'])) {
            $parent = StudentParent::find($data['parent_id']);

            if ($parent) {
                $data['parent_name'] = $parent->name;
                $data['parent_phone'] = $parent->phone;
            }
        }

        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            $data['avatar_path'] = $data['avatar']->store('avatars', 'public');
        }

        unset($data['avatar']);

        return $data;
    }

    protected function generateCode(): string
    {
        $nextId = (Student::max('id') ?? 0) + 1;

        return 'STD'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }
}

