<?php

declare(strict_types=1);

namespace App\Extensions\Czim\Paperclip\Attachment;

use Carbon\Carbon;
use Czim\Paperclip\Attachment\Attachment as PaperclipAttachment;

class Attachment extends PaperclipAttachment
{
    public function url(?string $variant = null): ?string
    {
        $url = parent::url($variant);

        if ($url === null) {
            return null;
        }

        $updatedAt = Carbon::parse($this->updatedAt() ?? '0')->timestamp;

        return "{$url}?t={$updatedAt}";
    }
}
