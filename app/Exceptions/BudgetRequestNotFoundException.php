<?php

namespace App\Exceptions;

use Exception;

class BudgetRequestNotFoundException extends Exception
{
    protected $message = "This budget request was not found.";
}
