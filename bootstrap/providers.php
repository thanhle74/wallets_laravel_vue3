<?php
declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    ThanhAloha\AccountManagement\Providers\ApiServiceProvider::class,
    ThanhAloha\Support\Providers\SupportServiceProvider::class,
    ThanhAloha\Dashboard\Providers\DashboardServiceProvider::class,
    ThanhAloha\Transaction\Providers\TransactionServiceProvider::class,
    ThanhAloha\Wallet\Providers\WalletServiceProvider::class,
    Nwidart\Modules\LaravelModulesServiceProvider::class,
];
