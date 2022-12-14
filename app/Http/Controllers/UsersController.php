<?php

namespace App\Http\Controllers;

use App\Exceptions\User\UserNotFoundException;
use App\Helpers\JsonResponse;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Repositories\UserRepository;

class UsersController extends Controller
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
     * list users
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = $this->userRepository->getList();
        return $this->response->success(['users' => $users]);
    }

    /**
     * show specific user
     *
     * @param string $username
     * @return JsonResponse
     */
    public function show($username)
    {
        try {
            $user = $this->userRepository->getByUsername($username);
            return $this->response->success(['user' => $user]);
        } catch (UserNotFoundException $e) {
            return $this->response->fail([], trans($e->getMessage()));
        }
    }

    /**
     * store a new user
     *
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        $user = $this->userRepository->create($request->validated());
        return $this->response->success(['user' => $user], trans('User Created Successfully'), 201);
    }

    /**
     * update user by username
     *
     * @param string $username
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update($username, UserUpdateRequest $request)
    {
        try {
            $user = $this->userRepository->getByUsername($username);
            $user = $this->userRepository->update($user, $request->validated());
            return $this->response->success($user, trans('User Updated Successfully'));
        } catch (UserNotFoundException $e) {
            return $this->response->fail([], trans($e->getMessage()));
        }
    }

    /**
     * delete user
     *
     * @param string $username
     * @return JsonResponse
     */
    public function delete($username)
    {
        try {
            $user = $this->userRepository->getByUsername($username);
            $this->userRepository->delete($user);
            return $this->response->success([], trans('User Deleted Successfully'), 200);
        } catch (UserNotFoundException $e) {
            return $this->response->fail([], trans($e->getMessage()));
        }
    }
}
