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

        $dataRequest['description'] = '';

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::BAD_REQUEST);

        $this->assertCount(1, BudgetRequest::all()->toArray());
    }

    /**
     * Create a budget request and then modify it's title, description and category.
     *
     * @return void
     */
    public function testModifyAnExistingBudgetRequest()
    {
        $dataRequest = [
            'title' => 'I\'m a new BudgetRequest',
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'phone' => '665673769'
        ];

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        BudgetRequestCategory::create(['category' => 'Reformas Baños']);
        $this->assertCount(1, BudgetRequestCategory::all()->toArray());

        BudgetRequestCategory::create(['category' => 'Aire Acondicionado']);
        $this->assertCount(2, BudgetRequestCategory::all()->toArray());

        $budgetRequestId = 1;
        $modifiedCategoryId = 2;
        $dataRequestToModify = [
            'title' => 'I\'m a modified BudgetRequest',
            'description' => 'I\'m a modified description',
            'category' => 'Aire Acondicionado'
        ];

        $this->put(route('budget_requests.update', $budgetRequestId), $dataRequestToModify)
            ->assertStatus(HttpStatusCode::OK);

        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $this->assertEquals($budgetRequest->title, $dataRequestToModify['title']);
        $this->assertEquals($budgetRequest->description, $dataRequestToModify['description']);
        $this->assertEquals($budgetRequest->budget_request_category_id, $modifiedCategoryId);
    }

    /**
     * Create a budget request and update it's status to PUBLISHED. After that, it tries to modify the
     * budget requests title. It throws an exception. It is not allowed to update a budget request while it hasn't
     * PENDING status.
     *
     * @return void
     */
    public function testModifyExistingBudgetRequestNonPending()
    {
        $dataRequest = [
            'title' => 'I\'m a new BudgetRequest',
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'phone' => '665673769'
        ];

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        $budgetRequestId = 1;
        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $budgetRequest->budget_request_status_id = BudgetRequestStatus::PUBLISHED_ID;
        $budgetRequest->save();

        $dataRequestToModify = [
            'title' => 'I\'m a modified BudgetRequest'
        ];

        $this->put(route('budget_requests.update', $budgetRequestId), $dataRequestToModify)
            ->assertStatus(HttpStatusCode::BAD_REQUEST);

        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $this->assertEquals($budgetRequest->title, $dataRequest['title']);
    }

    /**
     * Publish a new pending budget request.
     * @return void
     */
    public function testPublishBudgetRequest()
    {
        BudgetRequestCategory::create(['category' => 'Reformas Baños']);
        $this->assertCount(1, BudgetRequestCategory::all()->toArray());

        $budgetRequestId = 1;
        $dataRequest = [
            'title' => 'I\'m a new BudgetRequest',
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'phone' => '665673769',
            'category' => 'Reformas Baños'
        ];

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $this->assertTrue($budgetRequest->canBePublished);

        $this->put(route('budget_requests.publish', $budgetRequest->id))
            ->assertStatus(HttpStatusCode::OK);

        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $this->assertEquals(BudgetRequestStatus::PUBLISHED_ID, $budgetRequest->budget_request_status_id);
    }

    /**
     * Publish a budget request which was published before. It may produces an exception.
     *
     * @return void
     */
    public function testPublishOnePublishedBudgetRequest()
    {
        BudgetRequestCategory::create(['category' => 'Reformas Baños']);
        $this->assertCount(1, BudgetRequestCategory::all()->toArray());

        $budgetRequestId = 1;
        $dataRequest = [
            'title' => 'I\'m a new BudgetRequest',
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'phone' => '665673769',
            'category' => 'Reformas Baños'
        ];

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $this->assertTrue($budgetRequest->canBePublished);

        $this->put(route('budget_requests.publish', $budgetRequest->id))
            ->assertStatus(HttpStatusCode::OK);

        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $this->assertEquals(BudgetRequestStatus::PUBLISHED_ID, $budgetRequest->budget_request_status_id);

        $this->put(route('budget_requests.publish', $budgetRequest->id))
            ->assertStatus(HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Publish a budget request which has missing title.
     *
     * @return void
     */
    public function testPublishBudgetRequestWithMissingTitle()
    {
        BudgetRequestCategory::create(['category' => 'Reformas Baños']);
        $this->assertCount(1, BudgetRequestCategory::all()->toArray());

        $budgetRequestId = 1;
        $dataRequest = [
            'title' => '',
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'phone' => '665673769',
            'category' => 'Reformas Baños'
        ];

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $this->assertFalse($budgetRequest->canBePublished);

        $this->put(route('budget_requests.publish', $budgetRequest->id))
            ->assertStatus(HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Publish a budget request which has missing category.
     *
     * @return void
     */
    public function testPublishBudgetRequestWithMissingCategory()
    {
        $budgetRequestId = 1;
        $dataRequest = [
            'title' => 'This is a title.',
            'description' => $this->faker->paragraph,
            'email' => 'alejandro.suau@gmail.com',
            'address' => 'C/Cala Torta nº 1, 2º 2ª',
            'phone' => '665673769'
        ];

        $this->post(route('budget_requests.store'), $dataRequest)
            ->assertStatus(HttpStatusCode::CREATED);

        $budgetRequest = BudgetRequest::find($budgetRequestId);
        $this->assertFalse($budgetRequest->canBePublished);

        $this->put(route('budget_requests.publish', $budgetRequest->id))
            ->assertStatus(HttpStatusCode::BAD_REQUEST);
    }
}
