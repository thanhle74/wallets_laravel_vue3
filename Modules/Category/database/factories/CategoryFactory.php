<?php
declare(strict_types=1);

namespace Modules\Category\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Modules\Support\Enums\Status;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Category\Models\Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'status' => $this->faker->randomElement([Status::ACTIVE->value, Status::DISABLED->value]),
            'user_id' => User::factory(),
        ];
    }
}
