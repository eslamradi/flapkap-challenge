<?php

namespace App\Repositories;

use App\Exceptions\Auth\InvalidLoginException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * get users paginated
     *
     * @return LengthAwarePaginaroe
     */
    public function getList()
    {
        return User::paginate(10);
    }

    /**
     * get user by its id
     *
     * @param integer $id
     * @return User
     */
    public function getById(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new UserNotFoundException;
        }
        return $user;
    }

    /**
     * get user by its username
     *
     * @param string $username
     * @return User
     */
    public function getByUsername(string $username)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            throw new UserNotFoundException;
        }
        return $user;
    }

    /**
     * create new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return $user;
    }

    /**
     * update user
     *
     * @param string $username
     * @param array $data
     * @return User
     */
    public function updateByUsername(string $username, array $data)
    {
        $user = $this->getByUsername($username);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->fill($data);
        $user->save();

        return $user;
    }

    /**
     * delete user
     *
     * @param string $username
     * @return bool
     */
    public function deleteByUsername(string $username)
    {
        $user = $this->getByUsername($username);
        return $user->delete();
    }

    public function checkPasswordMatch(User $user, string $inputPassword)
    {
        return Hash::check($inputPassword, $user->password);
    }

    public function generateToken(User $user)
    {
        return $token = $user->createToken(config('app.name'))->plainTextToken;
    }

    public function login(array $data)
    {
        $user = $this->getByUsername($data['username']);
        if ($this->checkPasswordMatch($user, $data['password'])) {
            return $this->generateToken($user);
        }
        throw new InvalidLoginException;
    }
}
