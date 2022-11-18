<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Repositories\ProductRepository;

class ProductsController extends Controller
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
    public function index()
    {
        $products = $this->productRepository->getList();
        return $this->response->success(['products' => $products]);
    }

    /**
     * show specific product
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $product = $this->productRepository->getById($id);
        return $this->response->success(['product' => $product]);
    }

    /**
     * store a new product
     *
     * @param ProductStoreRequest $request
     * @return JsonResponse
     */
    public function store(ProductStoreRequest $request)
    {
        $product = $this->productRepository->create($request->validated());
        return $this->response->success(['product' => $product], trans('Product Created Successfully'), 201);
    }

    /**
     * update product by productname
     *
     * @param string $id
     * @param ProductUpdateRequest $request
     * @return JsonResponse
     */
    public function update($id, ProductUpdateRequest $request)
    {
        $product = $this->productRepository->getById($id);
        $product = $this->productRepository->update($product, $request->validated());
        return $this->response->success($product, trans('Product Updated Successfully'));
    }

    /**
     * delete product
     *
     * @param string $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $product = $this->productRepository->getById($id);
        $this->productRepository->delete($product);
        return $this->response->success([], trans('Product Deleted Successfully'), 200);
    }
}
