<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Subject;
use App\Repositories\GroupRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class GroupService
{
    public function __construct(
        protected GroupRepositoryInterface $groups
    ) {
    }

    public function list(): LengthAwarePaginator
    {
        return $this->groups->paginateWithRelations();
    }

    public function create(array $data): Group
    {
        $this->ensureSubjectMatchesGrade($data['subject_id'], $data['grade_id']);

        return $this->groups->create($data);
    }

    public function update(Group $group, array $data): Group
    {
        $this->ensureSubjectMatchesGrade($data['subject_id'], $data['grade_id']);

        return $this->groups->update($group, $data);
    }

    public function delete(Group $group): void
    {
        $this->groups->delete($group);
    }

    protected function ensureSubjectMatchesGrade(int $subjectId, int $gradeId): void
    {
        $subject = Subject::where('id', $subjectId)
            ->where('grade_id', $gradeId)
            ->exists();

        if (! $subject) {
            throw ValidationException::withMessages([
                'subject_id' => 'المادة لا تنتمي إلى المرحلة المختارة.',
            ]);
        }
    }
}

