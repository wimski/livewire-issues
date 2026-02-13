<?php

declare(strict_types=1);

namespace App\Extensions\Illuminate\Foundation\Console;

use Illuminate\Foundation\Console\ModelMakeCommand as Command;
use Illuminate\Support\Str;

class ModelMakeCommand extends Command
{
    /**
     * @return array<string, string>
     */
    protected function buildFactoryReplacements(): array
    {
        $replacements = [];

        if ($this->option('factory') || $this->option('all')) {
            $modelPath    = Str::of($this->argument('name'))->studly()->replace('/', '\\')->toString();
            $factory      = "{$modelPath}Factory";
            $factoryClass = Str::of($factory)->explode('\\')->last();

            $factoryCode = "/**
     * @use HasFactory<{$factoryClass}>
     */
    use HasFactory;
";

            $docblock = "/**
 * @method static {$factoryClass} factory(\$count = null, \$state = [])
 */";

            $replacements['{{ factory }}']            = $factoryCode;
            $replacements['{{ factoryImport }}']      = 'use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;';
            $replacements['{{ modelFactoryImport }}'] = "use Database\\Factories\\{$factory};";
            $replacements['{{ factoryDocblock }}']    = $docblock;
        } else {
            $replacements['{{ factory }}']                 = '//';
            $replacements["{{ factoryImport }}\n"]         = '';
            $replacements["{{ factoryImport }}\r\n"]       = '';
            $replacements["{{ modelFactoryImport }}\n"]    = '';
            $replacements["{{ modelFactoryImport }}\r\n"]  = '';
            $replacements["\n{{ factoryDocblock }}\n"]     = '';
            $replacements["\r\n{{ factoryDocblock }}\r\n"] = '';
        }

        return $replacements;
    }
}
