<?php

namespace App\Exceptions;

use Exception;

class BudgetRequestAlreadyDiscardedException extends Exception
{
    protected $message = "This budget request is already discarded.";
}
