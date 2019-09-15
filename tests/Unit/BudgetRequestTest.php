<?php

namespace Tests\Unit;

use App\HttpStatusCode;
use App\BudgetRequest;
use App\BudgetRequestStatus;
use App\User;
use App\BudgetRequestCategory;
use Tests\TestCase;

class BudgetRequestTest extends TestCase
{
    /**
     * Create a basic budget request with a non-existent user. It also creates the user.
     *
     * @return void
     */
    public function testCreateNewBudgetRequestWithANewUser()
    {
        $dataRequest = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'phone' => '665673769',
            'address' => 'C/Cala Torta nº 1, 2º 2ª'
        ];

        $budgetRequestExpected = new BudgetRequest;
        $budgetRequestExpected->id = 1;
        $budgetRequestExpected->title = $dataRequest['title'];
        $budgetRequestExpected->description = $dataRequest['description'];
        $budgetRequestExpected->user_id = 1;
        $budgetRequestExpected->budget_request_status_id = BudgetRequestStatus::PENDING_ID;

        $this->assertCount(0, BudgetRequest::all());
        $this->assertCount(0, User::all());

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED)
            ->assertJson($budgetRequestExpected->toArray());

        $this->assertCount(1, User::all());
        $this->assertCount(1, BudgetRequest::all());
    }

    /**
     * Create 2 basic budget request with the same user.
     * The second time, the user's phone and address are modified.
     *
     * @return void
     */
    public function testCreateNewBudgetRequestWithAnExistingUser()
    {
        $dataRequest = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'phone' => '665673769',
            'address' => 'C/Cala Torta nº 1, 2º 2ª'
        ];

        $budgetRequestExpected = new BudgetRequest;
        $budgetRequestExpected->id = 1;
        $budgetRequestExpected->title = $dataRequest['title'];
        $budgetRequestExpected->description = $dataRequest['description'];
        $budgetRequestExpected->user_id = 1;
        $budgetRequestExpected->budget_request_status_id = BudgetRequestStatus::PENDING_ID;

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED)
            ->assertJson($budgetRequestExpected->toArray());

        $dataRequest['phone'] = '665676869 (Modified One)';
        $dataRequest['address'] = 'C/C Modified One';

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        $this->assertCount(2, BudgetRequest::all());

        $user = User::find(1);
        $this->assertEquals($user->email, $dataRequest['email']);
        $this->assertEquals($user->phone, $dataRequest['phone']);
        $this->assertEquals($user->address, $dataRequest['address']);

        $this->assertCount(1, User::all());
    }

    /**
     * Try to create a new budget request with a non-existent category name in the database. It returns an error.
     * An error is occurred when tries to create a new budget request with a non-existent category name.
     *
     * @return void
     */
    public function testCreateNewBudgetRequestWithACategoryNotExist()
    {
        $dataRequest = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'phone' => '665673769',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'category' => 'Non-Existente Category Name'
        ];

        $this->assertCount(0, BudgetRequest::all()->toArray());

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::BAD_REQUEST);

        $this->assertCount(0, BudgetRequest::all()->toArray());
    }

    /**
     * Try to create a new budget request with a non-existent category name in the database. It returns an error.
     * An error is occurred when tries to create a new budget request with a non-existent category name.
     *
     * @return void
     */
    public function testCreateNewBudgetRequestWithAnExistingCategory()
    {
        BudgetRequestCategory::create(['category' => 'Reformas Baños']);
        $this->assertCount(1, BudgetRequestCategory::all()->toArray());

        $dataRequest = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'phone' => '665673769',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'category' => 'Reformas Baños'
        ];

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        $this->assertCount(1, BudgetRequest::all()->toArray());
    }

    /**
     * Try to create a new budget request with playing with the parameters. It just insert the first one.
     *
     * @return void
     */
    public function testCreateNewBudgetRequestWithMissingParameters()
    {
        $dataRequest = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'phone' => '665673769'
        ];

        unset($dataRequest['title']);

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        unset($dataRequest['phone']);

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::BAD_REQUEST);

        $dataRequest['phone'] = '665673769';
        unset($dataRequest['address']);

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::BAD_REQUEST);

        unset($dataRequest['description']);

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::BAD_REQUEST);

        $this->assertCount(1, BudgetRequest::all()->toArray());
    }

    /*public function testModifyAnExistingBudgetRequest()
    {
        $dataRequest = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'phone' => '665673769'
        ];
    }

    public function testModifyAnExistingBudgetRequestWithNonPendingStatus()
    {

    }*/
}
