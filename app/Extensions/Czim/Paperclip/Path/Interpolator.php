<?php

declare(strict_types=1);

namespace App\Extensions\Czim\Paperclip\Path;

use Czim\Paperclip\Contracts\AttachmentDataInterface;
use Czim\Paperclip\Path\Interpolator as PaperclipInterpolator;

class Interpolator extends PaperclipInterpolator
{
    /**
     * @return array<string, string>
     */
    protected function interpolations(): array
    {
        return array_merge(parent::interpolations(), [
            ':morph_map' => 'morphMap',
        ]);
    }

    protected function morphMap(AttachmentDataInterface $attachment): string
    {
        $class = $attachment->getInstanceClass();

        return new $class()->getMorphClass();
    }
}
