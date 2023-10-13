<?php

namespace App;

use Exception;

class AssetToBorrowException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}