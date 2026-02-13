<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Artisan;
use Tests\stubs\Laravel\app\Models\ModelWithAttachment;

trait DependsOnStubbedApp
{
    use ResolvesStubsPaths;

    protected function bootStubbedApp(): void
    {
        $this->setupMorphMap();
        $this->runMigrations();
    }

    private function setupMorphMap(): void
    {
        Relation::enforceMorphMap([
            'model-with-attachment' => ModelWithAttachment::class,
        ]);
    }

    private function runMigrations(): void
    {
        Artisan::call('migrate', [
            '--path'           => $this->getMigrationsPath(),
            '--realpath'       => true,
            '--quiet'          => true,
            '--no-interaction' => true,
        ]);
    }

    private function getMigrationsPath(): string
    {
        $path = implode(DIRECTORY_SEPARATOR, [
            'Laravel',
            'database',
            'Migrations',
        ]);

        return $this->resolveStubPath($path);
    }
}
