<?php
declare(strict_types=1);

namespace Modules\AdminTool\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AdminToolService
{
    protected array $allowedCommands = [
        'cache:clear',
        'route:clear',
        'route:list',
        'config:clear',
        'view:clear',
        'migrate:fresh --seed',
    ];

    protected array $factoryMap = [
        'UserFactory' => \App\Models\User::class,
        'CategoryFactory' => \Modules\Category\Models\Category::class,
        'TransactionFactory' => \Modules\Transaction\Models\Transaction::class,
        'WalletFactory' => \Modules\Wallet\Models\Wallet::class
    ];

    protected array $seeders = [
        'UsersTableSeeder',
        'ProductsTableSeeder',
        'OrdersTableSeeder',
        'CategoriesTableSeeder'
    ];

    public function runCommand(string $command): array
    {
        if (!in_array($command, $this->allowedCommands)) {
            return ['error' => 'Command not allowed'];
        }

        Artisan::call($command);
        return [
            'message' => "Executed: {$command}",
            'output' => explode("\n", trim(Artisan::output()))
        ];
    }

    public function getFactories(): array
    {
        return array_keys($this->factoryMap);
    }

    public function getSeeders(): array
    {
        return $this->seeders;
    }

    public function runFactory(string $factory, int $count = 10): array
    {
        if (!isset($this->factoryMap[$factory]) || $count < 1) {
            return ['error' => 'Invalid factory or count'];
        }

        try {
            DB::beginTransaction();
            $this->factoryMap[$factory]::factory()->count($count)->create();
            DB::commit();
            return ['message' => "Factory {$factory} created {$count} records."];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => 'Factory execution failed: ' . $e->getMessage()];
        }
    }

    public function runSeeder(string $seeder): array
    {
        if (!in_array($seeder, $this->seeders)) {
            return ['error' => 'Invalid seeder'];
        }

        Artisan::call("db:seed", ['--class' => $seeder]);
        return [
            'message' => "Seeder {$seeder} executed.",
            'output' => explode("\n", trim(Artisan::output()))
        ];
    }
}
