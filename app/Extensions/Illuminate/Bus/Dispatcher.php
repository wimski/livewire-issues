<?php

declare(strict_types=1);

namespace App\Extensions\Illuminate\Bus;

use App\Contracts\JobDispatcherInterface;
use App\Contracts\Jobs\DispatchableInterface;
use Illuminate\Bus\Dispatcher as JobDispatcher;
use Illuminate\Bus\UniqueLock;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class Dispatcher extends JobDispatcher implements JobDispatcherInterface
{
    public function dispatchToQueue($command)
    {
        if (! $command instanceof DispatchableInterface) {
            return null;
        }

        if (! $this->shouldDispatch($command)) {
            return null;
        }

        return parent::dispatchToQueue($command);
    }

    protected function shouldDispatch(DispatchableInterface $job): bool
    {
        if (! in_array(ShouldBeUnique::class, class_implements($job))) {
            return true;
        }

        $lock = new UniqueLock(
            $this->container->make(Cache::class),
        );

        return $lock->acquire($job);
    }
}
