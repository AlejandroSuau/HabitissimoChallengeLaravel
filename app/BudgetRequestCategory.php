<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetRequestCategory extends Model
{
    protected $table = 'budget_request_categories';

    protected $fillable = [
        'category'
    ];
}
