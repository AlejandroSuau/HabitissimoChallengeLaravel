<?php

namespace App\Exceptions;

use Exception;

class BudgetRequestIsNotPendingException extends Exception
{
    protected $message = "This budget request is not pending.";
}
