<?php

use Illuminate\Database\Seeder;
use App\BudgetRequest;
use APp\BudgetRequestCategory;
use App\BudgetRequestStatus;
use App\User;
use Faker\Factory as Faker;

class BudgetRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $categoriesNum = count(BudgetRequestCategory::all());
        $statusNum = count(BudgetRequestStatus::all());
        $usersNum = count(User::all());
        $budgetRequestSeeds = 5;

        $i = 0;
        while ($i < $budgetRequestSeeds) {
            BudgetRequest::create([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'budget_request_category_id' => rand(1, $categoriesNum),
                'budget_request_status_id' => rand(1, $statusNum),
                'user_id' => rand(1, $usersNum)
            ]);
            $i ++;
        }
    }
}
