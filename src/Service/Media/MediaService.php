<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service\Media;

use App\Entity\Basic\Media;
use App\Service\Media\Transformer\MediaTransformer;
use App\Service\Media\Validation\MediaValidator;
use App\Service\Media\Writer\MediaWriter;

final readonly class MediaService
{
    public function __construct(
        private MediaTransformer $mediaTransformer,
        private MediaWriter $mediaWriter,
        private MediaValidator $mediaValidator,
    ) {
    }

    public function createMedia(mixed $media): Media
    {
        return $this->mediaTransformer->transform($media);
    }

    public function writeMedia(mixed $notWrittenMedia, Media $createdMedia): Media
    {
        return $this->mediaWriter->write($notWrittenMedia, $createdMedia);
    }

    public function validateMedia(Media $media): string|true
    {
        return $this->mediaValidator->validate($media);
    }
}
