<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
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
        $user = $this->userRepository->getByUsername($username);
        return $this->response->success(['user' => $user]);
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
        $user = $this->userRepository->updateByUsername($username, $request->validated());
        return $this->response->success($user, trans('User Updated Successfully'));
    }

    /**
     * delete user
     *
     * @param string $username
     * @return JsonResponse
     */
    public function delete($username)
    {
        $this->userRepository->deleteByUsername($username);
        return $this->response->success([], trans('User Deleted Successfully'), 204);
    }
}
