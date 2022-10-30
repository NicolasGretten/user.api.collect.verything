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
            'address_id' => 'address-00000000-0000-0000-000000000000',
            'store_id' => 'store-00000000-0000-0000-000000000000',
            'account_id' => 'account-00000000-0000-0000-000000000000',
        ];
    }
}
