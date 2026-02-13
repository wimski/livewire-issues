<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Extensions\Czim\Paperclip\Attachment;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\stubs\Laravel\app\Models\ModelWithAttachment;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class AttachmentTest extends AbstractIntegrationTestCase
{
    #[Test]
    public function it_adds_the_updated_at_timestamp_to_the_url(): void
    {
        Storage::fake('s3');
        Carbon::setTestNow('2024-01-01 00:00:00');

        $model = ModelWithAttachment::factory()->create();

        $url = $model->image->url();

        self::assertIsString($url);
        self::assertStringEndsWith('?t=1704067200', $url);
    }
}
