<?php

namespace App\Providers;

use App\Repositories\Eloquent\GradeRepository;
use App\Repositories\Eloquent\GroupRepository;
use App\Repositories\Eloquent\StudentParentRepository;
use App\Repositories\Eloquent\StudentRepository;
use App\Repositories\Eloquent\SubjectRepository;
use App\Repositories\Eloquent\TeacherRepository;
use App\Repositories\GradeRepositoryInterface;
use App\Repositories\GroupRepositoryInterface;
use App\Repositories\StudentParentRepositoryInterface;
use App\Repositories\StudentRepositoryInterface;
use App\Repositories\SubjectRepositoryInterface;
use App\Repositories\TeacherRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(StudentParentRepositoryInterface::class, StudentParentRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
    }

    public function boot(): void
    {
    }
}
