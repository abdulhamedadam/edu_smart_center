<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            GroupSeeder::class,
            ExpenseCategorySeeder::class,
            ExpenseSeeder::class,
            LessonSeeder::class,
            AttendanceSeeder::class,
            HomeworkSeeder::class,
            ExamSeeder::class,
            ExamResultSeeder::class,
            SubscriptionSeeder::class,
        ]);
    }
}
