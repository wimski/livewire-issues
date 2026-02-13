<?php

declare(strict_types=1);

namespace App\Providers;

use App\Extensions\Czim\Paperclip\Attachment\AttachmentFactory;
use Czim\FileHandling\Contracts\Storage\StorableFileFactoryInterface;
use Czim\FileHandling\Contracts\Support\ContentInterpreterInterface;
use Czim\FileHandling\Contracts\Support\MimeTypeHelperInterface;
use Czim\FileHandling\Contracts\Support\UrlDownloaderInterface;
use Czim\FileHandling\Storage\File\StorableFileFactory;
use Czim\FileHandling\Support\Download\UriValidator;
use Czim\Paperclip\Contracts\AttachmentFactoryInterface;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AttachmentFactoryInterface::class, AttachmentFactory::class);

        $this->registerStorableFileFactoryWithUriValidatorConfiguration();
    }

    protected function registerStorableFileFactoryWithUriValidatorConfiguration(): void
    {
        $this->app->singleton(StorableFileFactoryInterface::class, function (): StorableFileFactoryInterface {
            $uriValidator = new UriValidator();

            $uriValidator->allowRemoteUri();

            return new StorableFileFactory(
                $this->app->make(MimeTypeHelperInterface::class),
                $this->app->make(ContentInterpreterInterface::class),
                $this->app->make(UrlDownloaderInterface::class),
                $uriValidator,
            );
        });
    }
}
