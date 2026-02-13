<?php

declare(strict_types=1);

namespace Tests\Concerns;

trait ResolvesStubsPaths
{
    protected function resolveStubPath(string $path): string
    {
        $path = str_replace([
            '\\',
            '/',
        ], [
            DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR,
        ], $path);

        $path = ltrim($path, DIRECTORY_SEPARATOR);

        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $path;
    }
}
