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

    public function seller()
    {
        return $this->belongsTo(User::class, 'sellerId');
    }

    public function isAvailable()
    {
        return $this->amountAvailable > 0;
    }
}
