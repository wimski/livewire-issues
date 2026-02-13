<?php

namespace App\Contracts;

use App\Contracts\Jobs\DispatchableInterface;
use Illuminate\Contracts\Bus\QueueingDispatcher;
use Illuminate\Foundation\Bus\PendingChain;
use Illuminate\Support\Collection;

interface JobDispatcherInterface extends QueueingDispatcher
{
    /**
     * @param  Collection<array-key, DispatchableInterface>|array<array-key, DispatchableInterface> $jobs
     * @return PendingChain
     */
    public function chain($jobs);

    /**
     * @param  mixed $command
     * @param  mixed $handler
     * @return void
     */
    public function dispatchAfterResponse($command, $handler = null);
}
