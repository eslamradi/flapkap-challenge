<?php

namespace App\Repositories;

use App\Exceptions\Product\ProductNotFoundException;
use App\Models\Product;
use App\Models\User;

class ProductRepository
{
    /**
     * get products paginated
     *
     * @return LengthAwarePaginaroe
     */
    public function getList(User $seller = null)
    {
        $products = Product::query();
        if ($seller) {
            $products = $products->where('sellerId', $seller->id);
        }
        return $products->paginate(10);
    }

    /**
     * get product by its id and seller if provided
     *
     * @param integer $id
     * @param User $seller
     * @throws ProductNotFoundException
     * @return Product
     */
    public function getById(int $id, User $seller = null)
    {
        $product = Product::where('id', $id);
        if ($seller) {
            $product = $product->where('sellerId', $seller->id);
        }
        $product = $product->first();
        if (!$product) {
            throw new ProductNotFoundException;
        }
        return $product;
    }

    /**
     * get product by id and seller
     *
     * @param integer $id
     * @param User $user
     * @throws ProductNotFoundException
     * @return Product
     */
    public function getBySellerId(int $id, User $user)
    {
        $product = Product::where([
            'id' => $id,
            'sellerId' => $user->id
        ])->first();
        if (!$product) {
            throw new ProductNotFoundException;
        }
        return $product;
    }

    /**
     * create new product
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data)
    {
        $product = Product::create($data);
        return $product;
    }

    /**
     * update product
     *
     * @param string $productname
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data)
    {
        $product = $product->update($data);
        return $product;
    }

    /**
     * delete product
     *
     * @param string $productname
     * @return bool
     */
    public function delete(Product $product)
    {
        return $product->delete();
    }

    /**
     * check if user can access/modify product
     *
     * @param User $user
     * @param Product $product
     * @return boolean
     */
    public function canUserAccessProduct(User $user, Product $product)
    {
        return $product->sellerId == $user->id;
    }
}
