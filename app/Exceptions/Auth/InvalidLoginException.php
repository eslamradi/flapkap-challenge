<?php

namespace App\Exceptions\Auth;

use Exception;

class InvalidLoginException extends Exception
{
    /**
     * costruct error
     */
    public function __construct()
    {
        $this->message = trans('Incorrect username/password');
    }
}
