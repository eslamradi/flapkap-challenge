<?php

namespace App\Exceptions\Product;

use App\Models\Product;
use Exception;

class ProductInsufficientAmountException extends Exception
{
    /**
     * costruct error
     */
    public function __construct(Product $product)
    {
        $this->message = $product->productName . ' ' . trans(" only has ") . $product->amountAvailable . trans(" item(s) left ");
    }
}
