<?php

namespace App\Exceptions\Product;

use Exception;

class ProductOutOfStockException extends Exception
{
    /**
     * costruct error
     */
    public function __construct()
    {
        $this->message = trans('Product out of stock');
    }
}
