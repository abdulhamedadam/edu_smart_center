<?php

namespace App\Services;

use App\Models\Teacher;
use App\Repositories\TeacherRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TeacherService
{
    public function __construct(
        protected TeacherRepositoryInterface $teachers
    ) {
    }

    public function all(): Collection
    {
        return $this->teachers->allWithRelations();
    }

    public function list(): LengthAwarePaginator
    {
        return $this->teachers->paginateWithRelations();
    }

    public function create(array $data): Teacher
    {
        $subjects = $data['subjects'] ?? [];
        $groups = $data['groups'] ?? [];

        unset($data['subjects'], $data['groups']);

        $teacher = $this->teachers->create($data);

        $this->syncAssignments($teacher, $subjects, $groups);

        return $teacher;
    }

    public function update(Teacher $teacher, array $data): Teacher
    {
        $subjects = $data['subjects'] ?? [];
        $groups = $data['groups'] ?? [];

        unset($data['subjects'], $data['groups']);

        $teacher = $this->teachers->update($teacher, $data);

        $this->syncAssignments($teacher, $subjects, $groups);

        return $teacher;
    }

    public function delete(Teacher $teacher): void
    {
        $teacher->subjects()->detach();

        $this->teachers->delete($teacher);
    }

    protected function syncAssignments(Teacher $teacher, array $subjects, array $groups): void
    {
        $pivotData = [];

        foreach ($subjects as $subjectId) {
            foreach ($groups as $groupId) {
                $pivotData[] = [
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subjectId,
                    'group_id' => $groupId,
                ];
            }
        }

        $teacher->subjects()->detach();

        if ($pivotData) {
            $teacher->subjects()->attach(
                collect($pivotData)->mapWithKeys(function ($row) {
                    return [
                        $row['subject_id'] => ['group_id' => $row['group_id']],
                    ];
                })
            );
        }
    }
}
