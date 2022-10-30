<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::factory()->create([
            'id'=> 'user-00000000-0000-0000-000000000001',
            'address_id' => 'address-00000000-0000-0000-000000000000',
            'store_id' => 'store-00000000-0000-0000-000000000000',
            'account_id' => 'account-00000000-0000-0000-000000000000',
        ]);
    }
}
