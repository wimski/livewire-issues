<?php

declare(strict_types=1);

namespace Tests\Suites\Http;

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

abstract class AbstractHttpTestCase extends AbstractIntegrationTestCase
{
    use MakesHttpRequests;
}
