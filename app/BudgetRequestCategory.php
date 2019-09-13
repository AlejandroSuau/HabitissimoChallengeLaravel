<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetRequestCategory extends Model
{
    public $timestamps = false;

    protected $table = 'budget_request_categories';
    protected $fillable = [
        'category'
    ];
}
