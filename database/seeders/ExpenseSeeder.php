<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ExpenseCategory::all()->keyBy('name');

        if ($categories->isEmpty()) {
            return;
        }

        $now = Carbon::now();
        $currentMonth = $now->copy()->startOfMonth();
        $previousMonth = $now->copy()->subMonthNoOverflow()->startOfMonth();

        $data = [
            [
                'name' => 'إيجار المكان',
                'monthly' => true,
                'amount' => 8000,
            ],
            [
                'name' => 'فواتير الكهرباء',
                'monthly' => true,
                'amount' => 2500,
            ],
            [
                'name' => 'الإنترنت والاتصالات',
                'monthly' => true,
                'amount' => 600,
            ],
            [
                'name' => 'رواتب الموظفين',
                'monthly' => true,
                'amount' => 12000,
            ],
            [
                'name' => 'دعاية وتسويق',
                'monthly' => false,
                'amount' => 1500,
            ],
            [
                'name' => 'مستلزمات مكتبية',
                'monthly' => false,
                'amount' => 900,
            ],
        ];

        foreach ([$previousMonth, $currentMonth] as $monthStart) {
            foreach ($data as $row) {
                $category = $categories->get($row['name']);

                if (! $category) {
                    continue;
                }

                $date = $monthStart->copy()->addDays(rand(0, 10))->toDateString();

                $description = $row['name'].' لشهر '.$monthStart->format('m-Y');

                Expense::updateOrCreate(
                    [
                        'expense_category_id' => $category->id,
                        'date' => $date,
                        'description' => $description,
                    ],
                    [
                        'amount' => $row['amount'],
                    ]
                );
            }
        }
    }
}

