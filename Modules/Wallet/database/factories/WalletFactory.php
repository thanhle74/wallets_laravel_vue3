<?php
declare(strict_types=1);

namespace Modules\Wallet\Database\Factories;

use App\Models\User;
use Modules\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Support\Enums\Type;
use Modules\Support\Enums\Status;

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
