<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'إيجار المكان',
            'فواتير الكهرباء',
            'الإنترنت والاتصالات',
            'رواتب الموظفين',
            'دعاية وتسويق',
            'مستلزمات مكتبية',
            'مصروفات أخرى',
        ];

        foreach ($categories as $name) {
            ExpenseCategory::updateOrCreate(['name' => $name], []);
        }
    }
}

