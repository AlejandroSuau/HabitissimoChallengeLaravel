<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('description');

            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('budget_categories')
                ->onDelete('cascade'); // 'On Cascade just to allow us to clean DB for every test'

            $table->integer('budget_request_status_id')->unsigned();
            $table->foreign('budget_request_status_id')->references('id')->on('budget_request_status')
                ->onDelete('cascade'); // 'On Cascade just to allow us to clean DB for every test'

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade'); // 'On Cascade just to allow us to clean DB for every test'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_requests');
    }
}
