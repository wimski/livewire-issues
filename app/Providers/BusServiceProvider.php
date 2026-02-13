<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\JobDispatcherInterface;
use App\Extensions\Illuminate\Bus\Dispatcher;
use Illuminate\Bus\BusServiceProvider as ServiceProvider;
use Illuminate\Bus\Dispatcher as JobDispatcher;
use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;

class BusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        parent::register();

        $dispatcher = $this->makeDispatcher();

        $this->app->singleton(JobDispatcherInterface::class, fn (): Dispatcher => $dispatcher);
        $this->app->instance(JobDispatcher::class, $dispatcher);
    }

    /**
     * @return array<array-key, class-string>
     */
    public function provides(): array
    {
        /** @var array<array-key, class-string> $provides */
        $provides = parent::provides();

        return array_merge($provides, [
            JobDispatcherInterface::class,
        ]);
    }

    protected function makeDispatcher(): Dispatcher
    {
        return new Dispatcher($this->app, function (?string $connection = null) {
            /** @var QueueFactoryContract $queueFactory */
            $queueFactory = $this->app->make(QueueFactoryContract::class);

            return $queueFactory->connection($connection);
        });
    }
}
