<?php

use Illuminate\Database\Seeder;

use App\BudgetRequestStatus;

class BudgetRequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BudgetRequestStatus::create(['status' => 'pending']);
        BudgetRequestStatus::create(['status' => 'published']);
        BudgetRequestStatus::create(['status' => 'discarded']);
    }
}
