<?php

namespace App\Exceptions\User;

use Exception;

class InsufficientDepositException extends Exception
{
    /**
     * costruct error
     */
    public function __construct($extraAmount)
    {
        $this->message = trans(" you don't have enough deposit to purchase these items, please add ") . $extraAmount . trans(' coint');
    }
}
