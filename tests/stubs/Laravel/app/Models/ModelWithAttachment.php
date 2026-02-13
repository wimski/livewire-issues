<?php

declare(strict_types=1);

namespace Tests\stubs\Laravel\app\Models;

use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Contracts\AttachmentInterface;
use Czim\Paperclip\Model\PaperclipTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\stubs\Laravel\database\Factories\ModelWithAttachmentFactory;

/**
 * @property AttachmentInterface $image
 */
class ModelWithAttachment extends Model implements AttachableInterface
{
    /**
     * @use HasFactory<ModelWithAttachmentFactory>
     */
    use HasFactory;
    use PaperclipTrait;

    public function __construct(array $attributes = [])
    {
        $this->hasAttachedFile('image');

        parent::__construct($attributes);
    }

    protected static function newFactory(): ModelWithAttachmentFactory
    {
        return new ModelWithAttachmentFactory();
    }
}
