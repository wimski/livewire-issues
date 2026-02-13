<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Extensions\Czim\Paperclip\Attachment;

use App\Extensions\Czim\Paperclip\Attachment\Attachment;
use App\Extensions\Czim\Paperclip\Attachment\AttachmentFactory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\Test;
use Tests\stubs\Laravel\app\Models\ModelWithAttachment;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class AttachmentFactoryTest extends AbstractIntegrationTestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_creates_an_attachment(): void
    {
        $instance = Mockery::mock(ModelWithAttachment::class);

        $factory = new AttachmentFactory();

        $attachment = $factory->create($instance, 'foo');

        self::assertInstanceOf(Attachment::class, $attachment);
    }
}
