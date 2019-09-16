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
    protected $hidden = ['budget_request_category_id', 'budget_request_status_id', 'user_id'];
    protected $with = ['user', 'category', 'status'];

    /**
     * Return all the budget request associated to this user email.
     *
     * @param $query
     * @param $email
     * @return mixed
     */
    public function scopeAllOfThisUser($query, $email)
    {
        return $query->join('users', 'budget_requests.user_id', '=', 'users.id')
            ->where('users.email', $email);
    }

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
        return $this->belongsTo('App\BudgetRequestCategory', 'budget_request_category_id');
    }

    public function status()
    {
        return $this->belongsTo('App\BudgetRequestStatus', 'budget_request_status_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
