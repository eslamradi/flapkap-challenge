<?php

namespace App\Exceptions\Product;

use App\Models\Product;
use Exception;

class ProductOutOfStockException extends Exception
{
    /**
     * costruct error
     */
    public function __construct(Product $product)
    {
        $this->message = $product->productName . ' ' . trans("out of stock");
    }
}
