<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetRequest extends Model
{
    public $timestamps = false;

    protected $table = 'budget_requests';
    protected $fillable = [
        'title', 'description', 'budget_request_category_id', 'budget_request_status_id', 'user_id'
    ];

    /**
     * One budget request can be published only if it complies those requirements:
     * - has a title.
     * - has a category.
     * - has pending status.
     * @return bool
     */
    public function getCanBePublishedAttribute() : bool
    {
        return (!is_null($this->title) && $this->title != ""
            && !is_null($this->budget_request_category_id) && $this->budget_request_category_id != ""
            && $this->budget_request_status_id == BudgetRequestStatus::PENDING_ID);
    }

    public function getIsPendingAttribute()
    {
        return $this->budget_request_status_id == BudgetRequestStatus::PENDING_ID;
    }

    public function getWasDiscardedAttribute()
    {
        return $this->budget_request_status_id == BudgetRequestStatus::DISCARDED_ID;
    }

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
