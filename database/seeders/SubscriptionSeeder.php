<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::with('subject')->get();
        $students = Student::all();

        if ($groups->isEmpty() || $students->isEmpty()) {
            return;
        }

        $now = Carbon::now();
        $month = (int) $now->format('m');
        $year = (int) $now->format('Y');

        foreach ($groups as $group) {
            $groupStudents = $students->where('grade_id', $group->grade_id)->take(10);

            foreach ($groupStudents as $student) {
                $dueDate = Carbon::create($year, $month, 5)->toDateString();

                $amount = $group->monthly_fee
                    ?? $group->subject?->monthly_fee
                    ?? 300;

                $subscription = Subscription::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'group_id' => $group->id,
                        'due_date' => $dueDate,
                    ],
                    [
                        'amount' => $amount,
                        'paid' => 0,
                    ]
                );

                if (rand(1, 100) <= 70) {
                    $paidAmount = $amount;

                    SubscriptionPayment::updateOrCreate(
                        [
                            'subscription_id' => $subscription->id,
                            'paid_at' => $now->toDateString(),
                        ],
                        [
                            'amount' => $paidAmount,
                        ]
                    );

                    $subscription->update([
                        'paid' => $paidAmount,
                    ]);
                }
            }
        }
    }
}

