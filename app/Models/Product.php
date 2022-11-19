<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'productName',
        'amountAvailable',
        'cost',
        'sellerId',
    ];

    protected $with = [
        'seller'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'sellerId');
    }

    public function isAvailable()
    {
        return $this->amountAvailable > 0;
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    /**
     * delete product
     *
     * @param string $productname
     * @return bool
     */
    public function reduceStock(int $amount)
    {
        $this->amountAvailable = $this->amountAvailable - $amount;
        $this->save();
    }
}
