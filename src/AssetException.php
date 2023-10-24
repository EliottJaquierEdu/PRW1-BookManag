<?php

namespace App;

use Exception;

class AssetException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}