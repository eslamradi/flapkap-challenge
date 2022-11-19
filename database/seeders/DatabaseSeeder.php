<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $admin = User::factory()
            ->admin()
            ->create([
                'username' => 'admin'
            ]);

        $seller = User::factory()
            ->seller()
            ->create([
                'username' => 'seller'
            ]);

        $sellertest = User::factory()
            ->seller()
            ->create([
                'username' => 'sellertest'
            ]);

        $products = Product::factory()
            ->seller($seller)
            ->count(10)
            ->create();

        $seller = User::factory()
            ->buyer()
            ->create([
                'username' => 'buyer'
            ]);
    }
}
