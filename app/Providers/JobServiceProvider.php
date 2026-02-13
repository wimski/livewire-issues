<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\JobDispatcherInterface;
use Illuminate\Support\ServiceProvider;

class JobServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected array $map = [
        //
    ];

    public function boot(): void
    {
        $this->app->make(JobDispatcherInterface::class)->map($this->map);
    }
}
