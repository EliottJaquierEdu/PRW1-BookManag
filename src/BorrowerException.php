<?php

namespace App;

use Exception;

class BorrowerException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}