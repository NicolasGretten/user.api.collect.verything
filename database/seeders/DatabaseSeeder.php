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
            'id'=> '00000000-0000-0000-000000000001',
            'addressId' => '00000000-0000-0000-000000000002',
            'storeId' => '00000000-0000-0000-000000000003',
            'accountId' => '00000000-0000-0000-000000000000',
        ]);
    }
}
