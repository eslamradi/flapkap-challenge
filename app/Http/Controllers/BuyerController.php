<?php

namespace App\Http\Controllers;

use App\Exceptions\Product\ProductInsufficientAmountException;
use App\Exceptions\Product\ProductOutOfStockException;
use App\Exceptions\User\InsufficientDepositException;
use App\Helpers\JsonResponse;
use App\Http\Requests\BuyRequest;
use App\Http\Requests\User\DepositRequest;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;

class BuyerController extends Controller
{
    /**
     * User Repository to process user operations
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Response
     *
     * @var JsonResponse
     */
    protected $response;

    /**
     * construct controller with dependencies injected
     *
     * @param UserRepository $userRepository
     * @param JsonResponse $response
     */
    public function __construct(UserRepository $userRepository, JsonResponse $response)
    {
        $this->userRepository = $userRepository;
        $this->response = $response;
    }

    /**
     * deposit coins to user deposit
     *
     * @param DepositRequest $request
     * @return JsonResponse
     */
    public function deposit(DepositRequest $request)
    {
        $newdeposit = $this->userRepository->addDeposit(auth()->user(), $request->validated('amount'));
        return $this->response->success(['deposit' => $newdeposit]);
    }

    /**
     * reset user credit
     *
     * @return JsonResponse
     */
    public function reset()
    {
        $oldDeposit = $this->userRepository->resetDeposit(auth()->user());
        return $this->response->success(['deposit' => 0, 'oldDeposit' => $oldDeposit]);
    }

    /**
     * perform buy action
     *
     * @param BuyRequest $request
     * @return JsonResponse
     */
    public function buy(BuyRequest $request)
    {
        $user = auth()->user();

        try {
            $reciept = $this->userRepository->buy($user, $request->validated('products'));
            return $this->response->success(['reciept' => $reciept], trans('Purchase successful'));
        } catch (ProductOutOfStockException $e) {
            /**
             * http status code is set to 200 because these exceptions are not
             * due to bad input nor internal errors but a request that is not 
             * satisfied 
             */
            return $this->response->fail([], $e->getMessage(), 200);
        } catch (ProductInsufficientAmountException $e) {
            return $this->response->fail([], $e->getMessage(), 200);
        } catch (InsufficientDepositException $e) {
            return $this->response->fail([], $e->getMessage(), 200);
        }
    }
}
