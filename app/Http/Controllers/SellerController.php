<?php

namespace App\Http\Controllers;

use App\Exceptions\Product\ProductAccessDeniedException;
use App\Exceptions\Product\ProductNotFoundException;
use App\Helpers\JsonResponse;
use App\Http\Requests\Product\SellerProductRequest;
use App\Repositories\ProductRepository;

class SellerController extends Controller
{
    /**
     * Product Repository to process product operations
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Response
     *
     * @var JsonResponse
     */
    protected $response;

    /**
     * construct controller with dependencies injected
     *
     * @param ProductRepository $productRepository
     * @param JsonResponse $response
     */
    public function __construct(ProductRepository $productRepository, JsonResponse $response)
    {
        $this->productRepository = $productRepository;
        $this->response = $response;
    }

    /**
     * list products
     *
     * @return JsonResponse
     */
    public function listProduct()
    {
        $products = $this->productRepository->getList(auth()->user());
        return $this->response->success(['products' => $products]);
    }

    /**
     * store a new product
     *
     * @param SellerProductRequest $request
     * @return JsonResponse
     */
    public function storeProduct(SellerProductRequest $request)
    {
        $product = $this->productRepository->create(array_merge(
            $request->validated(),
            ['sellerId' => auth()->user()->id]
        ));
        return $this->response->success(['product' => $product], trans('Product Created Successfully'), 201);
    }

    /**
     * update product by productname
     *
     * @param string $id
     * @param SellerProductRequest $request
     * @return JsonResponse
     */
    public function updateProduct($id, SellerProductRequest $request)
    {
        try {
            $product = $this->productRepository->getById($id);
            if (!$this->productRepository->canUserAccessProduct(auth()->user(), $product)) {
                throw new ProductAccessDeniedException;
            }
            $product = $this->productRepository->update($product, $request->validated());
            return $this->response->success($product, trans('Product Updated Successfully'));
        } catch (ProductNotFoundException $e) {
            return $this->response->fail([], trans($e->getMessage()));
        } catch (ProductAccessDeniedException $e) {
            return $this->response->fail([], trans($e->getMessage()), 403);
        }
    }

    /**
     * delete product
     *
     * @param string $id
     * @return JsonResponse
     */
    public function deleteProduct($id)
    {
        try {
            $product = $this->productRepository->getById($id);
            if (!$this->productRepository->canUserAccessProduct(auth()->user(), $product)) {
                throw new ProductAccessDeniedException;
            }
            $this->productRepository->delete($product);
            return $this->response->success([], trans('Product Deleted Successfully'), 200);
        } catch (ProductNotFoundException $e) {
            return $this->response->fail([], trans($e->getMessage()));
        } catch (ProductAccessDeniedException $e) {
            return $this->response->fail([], trans($e->getMessage()), 403);
        }
    }
}
