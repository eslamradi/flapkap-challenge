<?php

namespace App\Exceptions\Product;

use Exception;

class ProductNotFoundException extends Exception
{
    /**
     * costruct error
     */
    public function __construct()
    {
        $this->message = trans('Product Not Found');
    }
}
