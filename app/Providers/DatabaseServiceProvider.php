<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\DatabaseServiceProvider as ServiceProvider;
use Illuminate\Database\Grammar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\DB;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * @var array<array-key, string>
     */
    protected array $types = [
        'citext',
    ];

    public function register(): void
    {
        parent::register();

        Builder::$defaultMorphKeyType = 'uuid';
    }

    public function boot(): void
    {
        parent::boot();

        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );

        $this->bootTypes();
        $this->bootBlueprints();
    }

    protected function bootTypes(): void
    {
        foreach ($this->types as $type) {
            Grammar::macro('type' . ucfirst($type), fn (): string => $type);
        }
    }

    protected function bootBlueprints(): void
    {
        $this->bootBlueprintPrimaryUuidMacro();
        $this->bootBlueprintCaseInsensitiveMacro();
        $this->bootBlueprintAttachmentMacro();
        $this->bootBlueprintStringMorphsMacro();
    }

    protected function bootBlueprintPrimaryUuidMacro(): void
    {
        Blueprint::macro('primaryUuid', function (string $name = 'id'): ColumnDefinition {
            /** @var Blueprint $this */
            return $this->uuid($name)->primary()->default(DB::raw('uuid_generate_v4()'));
        });
    }

    protected function bootBlueprintCaseInsensitiveMacro(): void
    {
        Blueprint::macro('caseInsensitive', function (string $name): ColumnDefinition {
            /** @var Blueprint $this */
            return $this->addColumn('citext', $name);
        });
    }

    protected function bootBlueprintAttachmentMacro(): void
    {
        Blueprint::macro('attachment', function (string $name, bool $nullable = false): void {
            /** @var Blueprint $this */
            $this->string("{$name}_file_name")->nullable($nullable);
            $this->unsignedInteger("{$name}_file_size")->nullable($nullable);
            $this->string("{$name}_content_type")->nullable($nullable);
            $this->json("{$name}_variants")->nullable();
            $this->timestamp("{$name}_updated_at")->nullable($nullable);
        });
    }

    protected function bootBlueprintStringMorphsMacro(): void
    {
        Blueprint::macro('stringMorphs', function (string $name, ?string $indexName = null): void {
            /** @var Blueprint $this */
            $this->string("{$name}_type");
            $this->string("{$name}_id");
            $this->index(["{$name}_type", "{$name}_id"], $indexName);
        });
    }
}
