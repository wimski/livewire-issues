<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Extensions\Czim\Paperclip\Path;

use App\Extensions\Czim\Paperclip\Path\Interpolator;
use Czim\Paperclip\Contracts\AttachmentDataInterface;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\stubs\Laravel\app\Models\ModelWithAttachment;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class InterpolatorTest extends AbstractIntegrationTestCase
{
    protected Interpolator $interpolator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->interpolator = new Interpolator();
    }

    #[Test]
    public function it_interpolates_a_string(): void
    {
        $result = $this->interpolator->interpolate(
            ':morph_map',
            $this->mockAttachmentData(ModelWithAttachment::class),
        );

        self::assertSame('model-with-attachment', $result);
    }

    protected function mockAttachmentData(string $class): AttachmentDataInterface&MockInterface
    {
        $attachment = Mockery::mock(AttachmentDataInterface::class);
        $attachment
            ->expects('getInstanceClass')
            ->andReturn($class)
            ->getMock();

        return $attachment;
    }
}
