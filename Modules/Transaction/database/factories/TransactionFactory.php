<?php
declare(strict_types=1);

namespace Modules\Transaction\Database\Factories;

use Modules\Transaction\Models\Transaction;
use App\Models\User;
use Modules\Category\Models\Category;
use Modules\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? null, // Có thể null
            'wallet_id' => Wallet::inRandomOrder()->first()->id ?? Wallet::factory(),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'description' => fake()->sentence(),
            'transaction_date' => fake()->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
