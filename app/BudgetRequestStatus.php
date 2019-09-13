<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetRequestStatus extends Model
{
    const PENDING_ID = 1, PUBLISHED_ID = 2, DISCARDED_ID = 3;

    protected $table = 'budget_request_status';

    protected $fillable = [
        'status'
    ];
}
