<?php

namespace App\Exceptions;

use Exception;

class BudgetRequestCantBePublishedException extends Exception
{
    protected $message = "This budget request does not meet the requirements for publication.";
}
