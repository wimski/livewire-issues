<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Psr\Log\LogLevel;
use ReflectionClass;

class LoggingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->addSplitChannelForPerLevelLogging();
    }

    protected function addSplitChannelForPerLevelLogging(): void
    {
        /** @var array<string, array<string, mixed>> $channels */
        $channels = config_array('logging.channels');

        $split = [
            'driver'            => 'stack',
            'ignore_exceptions' => false,
            'channels'          => [],
        ];

        /** @var array<string, string> $levels */
        $levels = new ReflectionClass(LogLevel::class)->getConstants();

        foreach ($levels as $level) {
            $channel = "split_{$level}";

            $split['channels'][] = $channel;

            $channels[$channel] = [
                'driver' => 'single',
                'bubble' => false,
                'path'   => storage_path("logs/laravel-{$level}.log"),
                'level'  => $level,
            ];
        }

        $channels['split'] = $split;

        config(['logging.channels' => $channels]);
    }
}
