<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\Jobs\DispatchableInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

abstract class AbstractQueuedJob implements DispatchableInterface, ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public bool $failOnTimeout = true;
    public int $maxExceptions  = 1;
    public int $timeout        = 30;
    public int $tries          = 1;
}
