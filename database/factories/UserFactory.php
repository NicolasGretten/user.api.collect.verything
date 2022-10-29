<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'addressId' => '00000000-0000-0000-000000000002',
            'storeId' => '00000000-0000-0000-000000000003',
            'accountId' => '00000000-0000-0000-000000000000',
        ];
    }
}
