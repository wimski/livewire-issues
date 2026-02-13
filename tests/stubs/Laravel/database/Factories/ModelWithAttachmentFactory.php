<?php

declare(strict_types=1);

namespace Tests\stubs\Laravel\database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Tests\stubs\Laravel\app\Models\ModelWithAttachment;

/**
 * @extends Factory<ModelWithAttachment>
 */
class ModelWithAttachmentFactory extends Factory
{
    protected $model = ModelWithAttachment::class;

    public function definition(): array
    {
        return [
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];
    }
}
