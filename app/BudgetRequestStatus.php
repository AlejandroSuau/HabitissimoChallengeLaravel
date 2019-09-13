<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetRequestStatus extends Model
{
    const PENDING_ID = 1;
    const PUBLISHED_ID = 2;
    const DISCARDED_ID = 3;

    public $timestamps = false;

    protected $table = 'budget_request_status';
    protected $fillable = [
        'status'
    ];
}
