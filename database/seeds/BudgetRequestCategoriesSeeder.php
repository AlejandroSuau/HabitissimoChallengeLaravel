<?php

use Illuminate\Database\Seeder;

use App\BudgetRequestCategory;

class BudgetRequestCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BudgetRequestCategory::create(['category' => 'Calefacción']);
        BudgetRequestCategory::create(['category' => 'Reformas Cocinas']);
        BudgetRequestCategory::create(['category' => 'Reformas Baños']);
        BudgetRequestCategory::create(['category' => 'Aire Acondicionado']);
        BudgetRequestCategory::create(['category' => 'Construcción Casas']);
    }
}
