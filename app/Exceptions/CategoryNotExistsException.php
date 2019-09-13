<?php

namespace App\Exceptions;

use Exception;

class CategoryNotExistsException extends Exception
{
    protected $message = "Can not find any category with that name.";
}
