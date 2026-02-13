<?php

declare(strict_types=1);

namespace App\Providers;

use App\Extensions\Illuminate\Foundation\Console\ModelMakeCommand;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            ModelMakeCommand::class,
        ]);
    }

    public function boot(): void
    {
        Date::use(CarbonImmutable::class);

        $this->useMorphMap();
    }

    protected function useMorphMap(): void
    {
        /** @var array<string, class-string<Model>> $morphMap */
        $morphMap = config_array('morph-map');

        Relation::enforceMorphMap($morphMap);
    }
}
