<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Str;

trait UsesDatabase
{
    use RefreshDatabase {
        refreshTestDatabase as parentRefreshTestDatabase;
    }

    abstract protected function getApplication(): Application;

    protected function refreshTestDatabase(): void
    {
        try {
            if (! $this->shouldMigrate()) {
                RefreshDatabaseState::$migrated = true;
            }

            $this->parentRefreshTestDatabase();
        } catch (Exception $exception) {
            if (! $this->connectionError($exception)) {
                throw $exception;
            }

            sleep(3);

            $this->refreshTestDatabase();
        }
    }

    protected function shouldMigrate(): bool
    {
        $migrator = $this->getApplication()->make(Migrator::class);

        if (! $migrator->repositoryExists()) {
            return true;
        }

        $migrationDirectories = array_merge($migrator->paths(), [database_path('migrations')]);
        $migrationFiles       = array_keys($migrator->getMigrationFiles($migrationDirectories));
        $ran                  = $migrator->getRepository()->getRan();

        return $ran !== $migrationFiles;
    }

    protected function connectionError(Exception $exception): bool
    {
        return Str::of($exception->getMessage())->contains([
            'SQLSTATE[08006] [7]',
            'SQLSTATE[HY000] [2002]',
        ]);
    }
}
