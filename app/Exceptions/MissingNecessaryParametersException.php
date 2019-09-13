<?php

namespace App\Exceptions;

use Exception;

class MissingNecessaryParametersException extends Exception
{
    protected $message = "There are missing some necessary parameters.";
}
