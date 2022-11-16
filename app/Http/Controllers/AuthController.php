<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    protected $userRepository;
    protected $response;

    public function __construct(UserRepository $userRepository, JsonResponse $response)
    {
        $this->userRepository = $userRepository;
        $this->response = $response;
    }

    public function register(UserStoreRequest $request)
    {
        $user = $this->userRepository->create($request->validated());
        $token = $this->userRepository->generateToken($user);
        return $this->response->success(['token' => $token], trans('User registered successfully'));
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->userRepository->login($request->validated());
            return $this->response->success(['token' => $token], trans('User logged in successfully'));
        } catch (\Throwable $th) {
            return $this->response->fail([], trans('Incorrect username/password'));
        }
    }
}
