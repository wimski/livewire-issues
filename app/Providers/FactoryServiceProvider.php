<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Factories\Models\UserFactoryInterface;
use App\Factories\Models\UserFactory;
use Illuminate\Support\ServiceProvider;

class FactoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected array $factories = [
        UserFactoryInterface::class => UserFactory::class,
    ];

    public function register(): void
    {
        foreach ($this->factories as $contract => $concrete) {
            $this->app->singleton($contract, $concrete);
        }
    }
}
