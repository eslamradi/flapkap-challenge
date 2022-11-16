<?php

namespace App\Exceptions\User;

use Exception;

class UserNotFoundException extends Exception
{

    /**
     * costruct error
     */
    public function __construct()
    {
        $this->message = trans('User Not Found');
    }
}
