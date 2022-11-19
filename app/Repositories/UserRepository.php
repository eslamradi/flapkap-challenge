<?php

namespace App\Repositories;

use App\Exceptions\Auth\InvalidLoginException;
use App\Exceptions\Product\ProductInsufficientAmountException;
use App\Exceptions\Product\ProductOutOfStockException;
use App\Exceptions\User\InsufficientDepositException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\History;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
    public function update(User $user, array $data)
    {
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
    public function delete(User $user)
    {
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

    /**
     * add credit to user deposit
     *
     * @param User $user
     * @param int $amount
     * @return void
     */
    public function addDeposit(User $user, int $amount)
    {
        return $user->deposit($amount);
    }

    /**
     * reset user deposit to zero
     *
     * @param User $user
     * @return void
     */
    public function resetDeposit(User $user)
    {
        $deposit = $user->deposit;
        $user->deposit = 0;
        $user->save();

        return $deposit;
    }

    /**
     * buy action
     *
     * @param User $user
     * @param array $data
     * @return array 
     */
    public function buy(User $user, array $data)
    {
        $productIds = array_column($data, 'productId');

        $products = Product::whereIn('id', $productIds)->orderBy('id', 'desc')->get();

        /**
         * map products ids to amount to optimize complexity by avoiding nested loops
         */
        $amountsMappedByProduct = [];
        foreach ($data as $product) {
            $amountsMappedByProduct[$product['productId']] = $product['amount'];
        }

        $total = 0;

        foreach ($products as $product) {

            $amount = $amountsMappedByProduct[$product->id];

            if (!$product->isAvailable($amount)) {
                throw new ProductOutOfStockException($product);
            }

            if ($product->amountAvailable < $amount) {
                throw new ProductInsufficientAmountException($product);
            }
            $total = $amount * $product->cost;
        }


        if ($user->deposit < $total) {
            throw new InsufficientDepositException($total - $user->deposit);
        }

        $reciept = null;
        $history = [];

        DB::beginTransaction();

        try {
            $user->deduct($total);
            $productNames = [];

            foreach ($products as $product) {
                $amount = $amountsMappedByProduct[$product->id];
                $product->reduceStock($amount);
                $productNames[] = "{$product->seller->username}--{$product->productName}";

                $history[] = [
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'cost' => $amount * $product->cost,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            History::insert($history);

            $change = $user->deposit - $total;

            // round change to nearest coin amount that can be returned from the machine
            $change -= $change % 5;

            $reciept =  [
                'total' => $total,
                'change' => $change,
                'products' => $productNames
            ];
            DB::commit();
            return $reciept;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
