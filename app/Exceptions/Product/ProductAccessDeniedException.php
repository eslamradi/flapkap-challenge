<?php

namespace App\Exceptions\Product;

use Exception;

class ProductAccessDeniedException extends Exception
{
    /**
     * costruct error
     */
    public function __construct()
    {
        $this->message = trans('Product Access Denied');
    }
}
