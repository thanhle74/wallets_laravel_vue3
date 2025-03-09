<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Type;
use App\Enums\Status;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Wallet',
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'type' => $this->faker->randomElement([Type::CASH->value, Type::BANK->value, Type::CRYPTO->value]),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement([Status::ACTIVE->value, Status::DISABLED->value]),
        ];
    }
}
