<?php

declare(strict_types=1);

namespace App\Extensions\Czim\Paperclip\Attachment;

use Czim\Paperclip\Attachment\AttachmentFactory as PaperclipAttachmentFactory;
use Czim\Paperclip\Contracts\AttachmentInterface;

class AttachmentFactory extends PaperclipAttachmentFactory
{
    protected function createInstance(): AttachmentInterface
    {
        return new Attachment();
    }
}
