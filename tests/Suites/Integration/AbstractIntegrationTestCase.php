<?php

declare(strict_types=1);

namespace Tests\Suites\Integration;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;
use Tests\Concerns\CreatesApplication;
use Tests\Concerns\DependsOnStubbedApp;
use Tests\Concerns\UsesDatabase;

abstract class AbstractIntegrationTestCase extends TestCase
{
    use CreatesApplication;
    use DependsOnStubbedApp;
    use UsesDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bootStubbedApp();

        $this->app->make(Cache::class)->clear();
    }

    protected function getApplication(): Application
    {
        return $this->app;
    }
}
