<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Student;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('public_token', 64)
                ->unique()
                ->nullable()
                ->after('student_code');
        });

        Student::query()
            ->whereNull('public_token')
            ->chunkById(100, function ($students) {
                foreach ($students as $student) {
                    $student->public_token = Str::uuid()->toString();
                    $student->save();
                }
            });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('public_token');
        });
    }
};
