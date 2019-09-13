<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetRequest extends Model
{
    protected $table = 'budget_requests';

    protected $fillable = [
        'title', 'description', 'category_id', 'budget_request_status_id', 'user_id'
    ];


    public function category()
    {
        return $this->hasOne('App\BudgetRequestCategory');
    }

    public function status()
    {
        return $this->hasOne('App\BudgetRequestStatus');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }
}
