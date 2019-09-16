<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BudgetRequestStatusSeeder::class);
        $this->call(BudgetRequestCategoriesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(BudgetRequestSeeder::class);
    }
}
